import { Head } from '@inertiajs/react';
import { BookOpen, FileImage, FileText, LoaderCircle, Pencil, Plus, Search, Trash2 } from 'lucide-react';
import { useEffect, useMemo, useState } from 'react';
import type { ChangeEvent, FormEvent } from 'react';
import { AlertMessage } from '@/components/alert-message';
import { ConfirmDialog } from '@/components/confirm-dialog';
import InputError from '@/components/input-error';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/app-layout';
import { http, HttpError } from '@/lib/http';
import type { ValidationErrors } from '@/lib/http';
import type { BreadcrumbItem } from '@/types';

type Peminjaman = {
    id: number;
    nama_peminjam: string;
    judul_buku: string;
    tanggal_pinjam: string;
    tanggal_kembali: string;
    status: 'Dipinjam' | 'Dikembalikan';
    berkas: string | null;
    nama_berkas: string | null;
    berkas_url: string | null;
};

type FormFields = Pick<Peminjaman, 'nama_peminjam' | 'judul_buku' | 'tanggal_pinjam' | 'tanggal_kembali' | 'status'>;
type Message = { type: 'success' | 'error'; text: string } | null;

const emptyForm: FormFields = {
    nama_peminjam: '',
    judul_buku: '',
    tanggal_pinjam: '',
    tanggal_kembali: '',
    status: 'Dipinjam',
};

const fields: { name: Exclude<keyof FormFields, 'status'>; label: string; type: string }[] = [
    { name: 'nama_peminjam', label: 'Nama Peminjam', type: 'text' },
    { name: 'judul_buku', label: 'Judul Buku', type: 'text' },
    { name: 'tanggal_pinjam', label: 'Tanggal Pinjam', type: 'date' },
    { name: 'tanggal_kembali', label: 'Tanggal Kembali', type: 'date' },
];

const urls = {
    data: '/peminjaman/data',
    store: '/peminjaman',
    edit: (id: number) => `/peminjaman/${id}/edit`,
    update: (id: number) => `/peminjaman/${id}`,
    destroy: (id: number) => `/peminjaman/${id}`,
};

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Peminjaman', href: '/peminjaman' }];

// Tautan berkas yang dibuka di tab baru, ikon dipilih berdasarkan ekstensi
function FileLink({ url, name }: { url: string | null; name: string | null }) {
    if (!url) {
        return <span className="text-muted-foreground">-</span>;
    }

    const Icon = name?.toLowerCase().endsWith('.pdf') ? FileText : FileImage;

    return (
        <a
            href={url}
            target="_blank"
            rel="noopener noreferrer"
            className="inline-flex items-center gap-1 text-primary underline-offset-4 hover:underline"
        >
            <Icon className="size-4" /> {name ?? 'Lihat berkas'}
        </a>
    );
}

export default function Index() {
    const [records, setRecords] = useState<Peminjaman[]>([]);
    const [loading, setLoading] = useState(true);
    const [keyword, setKeyword] = useState('');
    const [message, setMessage] = useState<Message>(null);
    const [dialogOpen, setDialogOpen] = useState(false);
    const [editing, setEditing] = useState<Peminjaman | null>(null);
    const [form, setForm] = useState<FormFields>(emptyForm);
    const [file, setFile] = useState<File | null>(null);
    const [fileInputKey, setFileInputKey] = useState(0);
    const [errors, setErrors] = useState<ValidationErrors>({});
    const [saving, setSaving] = useState(false);
    const [deleting, setDeleting] = useState<Peminjaman | null>(null);
    const [processingDelete, setProcessingDelete] = useState(false);

    const showError = (error: unknown, fallback: string) => {
        const text = error instanceof HttpError ? (error.data?.message ?? fallback) : fallback;
        setMessage({ type: 'error', text });
    };

    // Mengambil seluruh data peminjaman dari server (GET /peminjaman/data)
    const loadData = async () => {
        setLoading(true);
        try {
            setRecords(await http<Peminjaman[]>(urls.data));
        } catch (error) {
            showError(error, 'Gagal memuat data peminjaman.');
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        loadData();
    }, []);

    // Pencarian dilakukan di browser tanpa request baru ke server
    const filtered = useMemo(() => {
        const q = keyword.trim().toLowerCase();

        if (q === '') {
            return records;
        }

        return records.filter((item) =>
            [item.nama_peminjam, item.judul_buku, item.status].some((value) => value.toLowerCase().includes(q)),
        );
    }, [records, keyword]);

    const resetFileAndErrors = () => {
        setFile(null);
        setFileInputKey((key) => key + 1);
        setErrors({});
    };

    const openCreate = () => {
        setEditing(null);
        setForm(emptyForm);
        resetFileAndErrors();
        setDialogOpen(true);
    };

    // Mengambil satu data (GET /peminjaman/{id}/edit) lalu mengisi form di dialog
    const openEdit = async (id: number) => {
        try {
            const data = await http<Peminjaman>(urls.edit(id));

            setEditing(data);
            setForm({
                nama_peminjam: data.nama_peminjam,
                judul_buku: data.judul_buku,
                tanggal_pinjam: data.tanggal_pinjam,
                tanggal_kembali: data.tanggal_kembali,
                status: data.status,
            });
            resetFileAndErrors();
            setDialogOpen(true);
        } catch (error) {
            showError(error, 'Data peminjaman tidak ditemukan.');
        }
    };

    const handleChange = (event: ChangeEvent<HTMLInputElement | HTMLSelectElement>) => {
        setForm({ ...form, [event.target.name]: event.target.value });
    };

    // Menyimpan data (tambah dan edit) menggunakan FormData agar berkas ikut terkirim
    const handleSubmit = async (event: FormEvent) => {
        event.preventDefault();
        setSaving(true);
        setErrors({});

        const formData = new FormData();

        Object.entries(form).forEach(([key, value]) => formData.append(key, value));

        if (file) {
            formData.append('berkas', file);
        }

        if (editing) {
            formData.append('_method', 'PUT');
        }

        try {
            const result = await http<{ success: string }>(editing ? urls.update(editing.id) : urls.store, {
                method: 'POST',
                body: formData,
            });

            setDialogOpen(false);
            setMessage({ type: 'success', text: result.success });
            await loadData();
        } catch (error) {
            if (error instanceof HttpError && error.status === 422) {
                setErrors(error.data?.errors ?? {});
            } else {
                showError(error, 'Terjadi kesalahan, silakan coba lagi.');
            }
        } finally {
            setSaving(false);
        }
    };

    // Menghapus data (DELETE /peminjaman/{id}) setelah dikonfirmasi
    const handleDelete = async () => {
        if (!deleting) {
            return;
        }

        setProcessingDelete(true);
        try {
            const result = await http<{ success: string }>(urls.destroy(deleting.id), { method: 'DELETE' });
            setMessage({ type: 'success', text: result.success });
            await loadData();
        } catch (error) {
            showError(error, 'Data peminjaman gagal dihapus.');
        } finally {
            setProcessingDelete(false);
            setDeleting(null);
        }
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Peminjaman" />

            <div className="flex flex-1 flex-col gap-4 p-4">
                <div className="flex flex-wrap items-center justify-between gap-2">
                    <div>
                        <h1 className="flex items-center gap-2 text-xl font-semibold">
                            Data Peminjaman <Badge variant="secondary">{records.length}</Badge>
                        </h1>
                        <p className="text-sm text-muted-foreground">
                            Seluruh operasi pada halaman ini berjalan dengan AJAX tanpa memuat ulang halaman.
                        </p>
                    </div>
                    <Button onClick={openCreate}>
                        <Plus /> Tambah Peminjaman
                    </Button>
                </div>

                <AlertMessage type={message?.type ?? 'success'} message={message?.text} onClose={() => setMessage(null)} />

                <div className="relative max-w-sm">
                    <Search className="absolute top-1/2 left-3 size-4 -translate-y-1/2 text-muted-foreground" />
                    <Input
                        value={keyword}
                        onChange={(event) => setKeyword(event.target.value)}
                        placeholder="Cari nama peminjam, judul buku, status..."
                        className="pl-9"
                    />
                </div>

                <div className="overflow-x-auto rounded-xl border">
                    <table className="w-full text-sm">
                        <thead className="bg-muted/50 text-left">
                            <tr>
                                <th className="px-4 py-3 font-medium">No</th>
                                <th className="px-4 py-3 font-medium">Peminjam</th>
                                <th className="px-4 py-3 font-medium">Judul Buku</th>
                                <th className="px-4 py-3 font-medium">Tgl Pinjam</th>
                                <th className="px-4 py-3 font-medium">Tgl Kembali</th>
                                <th className="px-4 py-3 font-medium">Status</th>
                                <th className="px-4 py-3 font-medium">Bukti</th>
                                <th className="px-4 py-3 text-right font-medium">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            {loading && (
                                <tr>
                                    <td colSpan={8} className="px-4 py-8 text-center text-muted-foreground">
                                        <LoaderCircle className="mx-auto size-5 animate-spin" />
                                    </td>
                                </tr>
                            )}
                            {!loading && filtered.length === 0 && (
                                <tr>
                                    <td colSpan={8} className="px-4 py-8 text-center text-muted-foreground">
                                        {records.length === 0 ? 'Belum ada data peminjaman.' : 'Data tidak ditemukan.'}
                                    </td>
                                </tr>
                            )}
                            {!loading &&
                                filtered.map((item, index) => (
                                    <tr key={item.id} className="border-t">
                                        <td className="px-4 py-3">{index + 1}</td>
                                        <td className="px-4 py-3 font-medium">{item.nama_peminjam}</td>
                                        <td className="px-4 py-3">
                                            <span className="inline-flex items-center gap-1">
                                                <BookOpen className="size-4 text-muted-foreground" />
                                                {item.judul_buku}
                                            </span>
                                        </td>
                                        <td className="px-4 py-3">{item.tanggal_pinjam}</td>
                                        <td className="px-4 py-3">{item.tanggal_kembali}</td>
                                        <td className="px-4 py-3">
                                            <Badge variant={item.status === 'Dipinjam' ? 'default' : 'secondary'}>{item.status}</Badge>
                                        </td>
                                        <td className="px-4 py-3">
                                            <FileLink url={item.berkas_url} name={item.nama_berkas} />
                                        </td>
                                        <td className="px-4 py-3">
                                            <div className="flex justify-end gap-2">
                                                <Button size="sm" variant="outline" onClick={() => openEdit(item.id)}>
                                                    <Pencil /> Edit
                                                </Button>
                                                <Button size="sm" variant="destructive" onClick={() => setDeleting(item)}>
                                                    <Trash2 /> Hapus
                                                </Button>
                                            </div>
                                        </td>
                                    </tr>
                                ))}
                        </tbody>
                    </table>
                </div>
            </div>

            <Dialog open={dialogOpen} onOpenChange={(open) => !saving && setDialogOpen(open)}>
                <DialogContent className="sm:max-w-lg">
                    <form onSubmit={handleSubmit} noValidate className="space-y-4">
                        <DialogHeader>
                            <DialogTitle>{editing ? 'Edit Peminjaman' : 'Tambah Peminjaman'}</DialogTitle>
                            <DialogDescription>Lengkapi data peminjaman beserta foto bukti peminjaman.</DialogDescription>
                        </DialogHeader>

                        {fields.map((field) => (
                            <div key={field.name} className="grid gap-2">
                                <Label htmlFor={field.name}>
                                    {field.label} <span className="text-red-500">*</span>
                                </Label>
                                <Input
                                    id={field.name}
                                    name={field.name}
                                    type={field.type}
                                    value={form[field.name]}
                                    onChange={handleChange}
                                    aria-invalid={!!errors[field.name]}
                                />
                                <InputError message={errors[field.name]?.[0]} />
                            </div>
                        ))}

                        <div className="grid gap-2">
                            <Label htmlFor="status">
                                Status <span className="text-red-500">*</span>
                            </Label>
                            <select
                                id="status"
                                name="status"
                                value={form.status}
                                onChange={handleChange}
                                className="h-9 rounded-md border border-input bg-transparent px-3 text-sm shadow-xs"
                            >
                                <option value="Dipinjam">Dipinjam</option>
                                <option value="Dikembalikan">Dikembalikan</option>
                            </select>
                            <InputError message={errors.status?.[0]} />
                        </div>

                        <div className="grid gap-2">
                            <Label htmlFor="berkas">
                                Bukti Peminjaman {!editing && <span className="text-red-500">*</span>}
                            </Label>
                            <Input
                                key={fileInputKey}
                                id="berkas"
                                type="file"
                                accept=".jpg,.jpeg,.png,.pdf"
                                onChange={(event) => setFile(event.target.files?.[0] ?? null)}
                                aria-invalid={!!errors.berkas}
                            />
                            <p className="text-xs text-muted-foreground">
                                Format jpg, jpeg, png, atau pdf, maksimal 2 MB.
                                {editing && ' Kosongkan jika tidak ingin mengganti berkas.'}
                            </p>
                            {editing?.berkas_url && (
                                <p className="text-xs">
                                    Berkas saat ini: <FileLink url={editing.berkas_url} name={editing.nama_berkas} />
                                </p>
                            )}
                            <InputError message={errors.berkas?.[0]} />
                        </div>

                        <DialogFooter className="gap-2">
                            <Button type="button" variant="secondary" onClick={() => setDialogOpen(false)} disabled={saving}>
                                Batal
                            </Button>
                            <Button type="submit" disabled={saving}>
                                {saving && <LoaderCircle className="animate-spin" />}
                                {saving ? 'Menyimpan...' : 'Simpan'}
                            </Button>
                        </DialogFooter>
                    </form>
                </DialogContent>
            </Dialog>

            <ConfirmDialog
                open={deleting !== null}
                description={`Yakin ingin menghapus peminjaman ${deleting?.nama_peminjam} beserta berkasnya?`}
                processing={processingDelete}
                onConfirm={handleDelete}
                onOpenChange={(open) => !open && setDeleting(null)}
            />
        </AppLayout>
    );
}
