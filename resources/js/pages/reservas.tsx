import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import { ReservasTable } from '@/components/tables/reservas-table';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Reservas', href: '/reservas' },
];

export default function Reservas() {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Reservas" />
            <div className="p-4">
                <h1 className="mb-4 text-2xl font-bold">Reservas</h1>
                <p>Registro de Reservas.</p>
                <ReservasTable />
            </div>
        </AppLayout>
    );
}
