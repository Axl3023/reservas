import { MesaTable } from '@/components/tables/mesas-table';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Mesas', href: '/mesas' },
];

export default function Reservas() {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Mesas" />
            <div className="p-4">
                <h1 className="mb-4 text-2xl font-bold">Mesas</h1>
                <p>Registro de Mesas.</p>
                <MesaTable />
            </div>
        </AppLayout>
    );
}
