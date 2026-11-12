import { Head, Link, router } from '@inertiajs/react';
import { Pencil, Plus, Search, Trash2 } from 'lucide-react';
import { useState } from 'react';
import type { FormEvent } from 'react';
import { FlashMessage } from '@/components/alert-message';
import { ConfirmDialog } from '@/components/confirm-dialog';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem } from '@/types';

type BukuRow = {
    id: number;
    judul: string;
    penulis: string;
    penerbit: string;
    tahun_terbit: number;
    stok: number;
};

type PaginationLink = { url: string | null; label: string; active: boolean };

type IndexProps = {
    bukus: {
        data: BukuRow[];
        links: PaginationLink[];
        from: number | null;
        to: number | null;
        total: number;
    };
    filters: { search: string };
    canManage: boolean;
};

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Buku', href: '/buku' }];

export default function Index({ bukus, filters, canManage }: IndexProps) {
    const [search, setSearch] = useState(filters.search);
    const [deleting, setDeleting] = useState<BukuRow | null>(null);
    const [processing, setProcessing] = useState(false);

    // Kirim ulang request GET /buku dengan kata kunci pencarian
    const handleSearch = (event: FormEvent) => {
        event.preventDefault();
        router.get('/buku', { search }, { preserveState: true, replace: true });
    };

    const handleDelete = () => {
        if (!deleting) {
            return;
        }

        router.delete(`/buku/${deleting.id}`, {
            preserveScroll: true,
            onStart: () => setProcessing(true),
            onFinish: () => {
                setProcessing(false);
                setDeleting(null);
            },
        });
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Buku" />

            <div className="flex flex-1 flex-col gap-4 p-4">
                <div className="flex flex-wrap items-center justify-between gap-2">
                    <div>
                        <h1 className="text-xl font-semibold">Daftar Buku</h1>
                        <p className="text-sm text-muted-foreground">Total {bukus.total} judul buku di katalog perpustakaan.</p>
                    </div>
                    {canManage && (
                        <Button asChild>
                            <Link href="/buku/create">
                                <Plus /> Tambah Buku
                            </Link>
                        </Button>
                    )}
                </div>

                <FlashMessage />

                <form onSubmit={handleSearch} className="flex max-w-sm gap-2">
                    <Input value={search} onChange={(event) => setSearch(event.target.value)} placeholder="Cari judul, penulis, penerbit..." />
                    <Button type="submit" variant="secondary">
                        <Search /> Cari
                    </Button>
                </form>

                <div className="overflow-x-auto rounded-xl border">
                    <table className="w-full text-sm">
                        <thead className="bg-muted/50 text-left">
                            <tr>
                                <th className="px-4 py-3 font-medium">No</th>
                                <th className="px-4 py-3 font-medium">Judul</th>
                                <th className="px-4 py-3 font-medium">Penulis</th>
                                <th className="px-4 py-3 font-medium">Penerbit</th>
                                <th className="px-4 py-3 font-medium">Tahun</th>
                                <th className="px-4 py-3 font-medium">Stok</th>
                                {canManage && <th className="px-4 py-3 text-right font-medium">Aksi</th>}
                            </tr>
                        </thead>
                        <tbody>
                            {bukus.data.length === 0 && (
                                <tr>
                                    <td colSpan={canManage ? 7 : 6} className="px-4 py-8 text-center text-muted-foreground">
                                        Data buku tidak ditemukan.
                                    </td>
                                </tr>
                            )}
                            {bukus.data.map((buku, index) => (
                                <tr key={buku.id} className="border-t">
                                    <td className="px-4 py-3">{(bukus.from ?? 1) + index}</td>
                                    <td className="px-4 py-3 font-medium">{buku.judul}</td>
                                    <td className="px-4 py-3">{buku.penulis}</td>
                                    <td className="px-4 py-3">{buku.penerbit}</td>
                                    <td className="px-4 py-3">{buku.tahun_terbit}</td>
                                    <td className="px-4 py-3">
                                        <Badge variant={buku.stok > 0 ? 'secondary' : 'destructive'}>{buku.stok}</Badge>
                                    </td>
                                    {canManage && (
                                        <td className="px-4 py-3">
                                            <div className="flex justify-end gap-2">
                                                <Button asChild size="sm" variant="outline">
                                                    <Link href={`/buku/${buku.id}/edit`}>
                                                        <Pencil /> Edit
                                                    </Link>
                                                </Button>
                                                <Button size="sm" variant="destructive" onClick={() => setDeleting(buku)}>
                                                    <Trash2 /> Hapus
                                                </Button>
                                            </div>
                                        </td>
                                    )}
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>

                <div className="flex flex-wrap items-center justify-between gap-2 text-sm">
                    <p className="text-muted-foreground">
                        Menampilkan {bukus.from ?? 0}-{bukus.to ?? 0} dari {bukus.total} data
                    </p>
                    <div className="flex flex-wrap gap-1">
                        {bukus.links.map((link, index) =>
                            link.url ? (
                                <Button key={index} asChild size="sm" variant={link.active ? 'default' : 'outline'}>
                                    <Link href={link.url} preserveScroll>
                                        {cleanLabel(link.label)}
                                    </Link>
                                </Button>
                            ) : (
                                <Button key={index} size="sm" variant="outline" disabled>
                                    {cleanLabel(link.label)}
                                </Button>
                            ),
                        )}
                    </div>
                </div>
            </div>

            <ConfirmDialog
                open={deleting !== null}
                description={`Yakin ingin menghapus buku ${deleting?.judul}? Tindakan ini tidak dapat dibatalkan.`}
                processing={processing}
                onConfirm={handleDelete}
                onOpenChange={(open) => !open && setDeleting(null)}
            />
        </AppLayout>
    );
}

// Label pagination Laravel berisi entitas HTML &laquo; dan &raquo;
function cleanLabel(label: string): string {
    return label.replace('&laquo;', '«').replace('&raquo;', '»');
}
