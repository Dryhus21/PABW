import { Link } from '@inertiajs/react';
import type { FormEvent } from 'react';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

export type BukuFormData = {
    judul: string;
    penulis: string;
    penerbit: string;
    tahun_terbit: string;
    stok: string;
};

type BukuFormProps = {
    data: BukuFormData;
    errors: Partial<Record<keyof BukuFormData, string>>;
    processing: boolean;
    submitLabel: string;
    setData: (key: keyof BukuFormData, value: string) => void;
    onSubmit: (event: FormEvent) => void;
};

const textFields: { name: keyof BukuFormData; label: string; type?: string }[] = [
    { name: 'judul', label: 'Judul' },
    { name: 'penulis', label: 'Penulis' },
    { name: 'penerbit', label: 'Penerbit' },
    { name: 'tahun_terbit', label: 'Tahun Terbit', type: 'number' },
    { name: 'stok', label: 'Stok', type: 'number' },
];

export function BukuForm({ data, errors, processing, submitLabel, setData, onSubmit }: BukuFormProps) {
    return (
        <form onSubmit={onSubmit} noValidate className="max-w-xl space-y-5 rounded-xl border p-6">
            {textFields.map((field) => (
                <div key={field.name} className="grid gap-2">
                    <Label htmlFor={field.name}>
                        {field.label} <span className="text-red-500">*</span>
                    </Label>
                    <Input
                        id={field.name}
                        type={field.type ?? 'text'}
                        value={data[field.name]}
                        onChange={(event) => setData(field.name, event.target.value)}
                        aria-invalid={!!errors[field.name]}
                    />
                    <InputError message={errors[field.name]} />
                </div>
            ))}

            <div className="flex gap-2">
                <Button type="submit" disabled={processing}>
                    {processing ? 'Menyimpan...' : submitLabel}
                </Button>
                <Button asChild variant="secondary">
                    <Link href="/buku">Batal</Link>
                </Button>
            </div>
        </form>
    );
}
