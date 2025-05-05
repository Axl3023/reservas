import { EmpleadosTable } from '@/components/tables/empleados-table';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Empleados', href: '/empleados' },
];

export default function Reservas() {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Empleados" />
            <div className="p-4">
                <h1 className="mb-4 text-2xl font-bold">Empleados</h1>
                <p>Registro de Empleados.</p>
                <EmpleadosTable />
            </div>
        </AppLayout>
    );
}
