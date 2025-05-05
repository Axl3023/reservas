'use client';

import { Badge } from '@/components/ui/badge';
import { Checkbox } from '@/components/ui/checkbox';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { cn } from '@/lib/utils';
import { ColumnDef, flexRender, getCoreRowModel, useReactTable } from '@tanstack/react-table';
import { useEffect, useState } from 'react';

// Tipo de dato para las filas
type ReservaItem = {
    id: number;
    customer_name: string;
    customer_phone: string;
    customer_email: string;
    reservation_date: string; // usar string porque viene como ISO string de JSON
    reservation_time: string;
    guest_count: number;
    table: {
        table_number: string;
    }
    status: 'pending' | 'confirmed' | 'cancelled' | 'completed'; // debe coincidir con la migración
    notes: string | null;
    employee: {
        first_name: string;
        last_name: string | null;
    }
};

// Columnas de la tabla
const columns: ColumnDef<ReservaItem>[] = [
    {
        id: 'select',
        header: ({ table }) => (
            <Checkbox
                checked={table.getIsAllPageRowsSelected() || (table.getIsSomePageRowsSelected() && 'indeterminate')}
                onCheckedChange={(value) => table.toggleAllPageRowsSelected(!!value)}
                aria-label="Select all"
            />
        ),
        cell: ({ row }) => (
            <Checkbox checked={row.getIsSelected()} onCheckedChange={(value) => row.toggleSelected(!!value)} aria-label="Select row" />
        ),
    },
    {
        header: 'Cliente',
        accessorKey: 'customer_name',
    },
    {
        header: 'Teléfono',
        accessorKey: 'customer_phone',
    },
    {
        header: 'Email',
        accessorKey: 'customer_email',
    },
    {
        header: 'Fecha',
        accessorKey: 'reservation_date',
    },
    {
        header: 'Hora',
        accessorKey: 'reservation_time',
    },
    {
        header: 'N° Invitados',
        accessorKey: 'guest_count',
    },
    {
        header: 'Mesa',
        accessorKey: 'table.table_number',
    },
    {
        header: 'Estado',
        accessorKey: 'status',
        cell: ({ row }) => (
            <Badge
                className={cn(
                    row.getValue('status') === 'cancelled' && 'bg-destructive text-white',
                    row.getValue('status') === 'completed' && 'bg-green-600 text-white',
                    row.getValue('status') === 'pending' && 'bg-yellow-600 text-white',
                    row.getValue('status') === 'confirmed' && 'bg-blue-600 text-white',
                )}
            >
                {row.getValue('status')}
            </Badge>
        ),
    },
    {
        header: 'Notas',
        accessorKey: 'notes',
    },
    {
        header: 'Empleado',
        accessorFn: (
            row, // Nombre completo
        ) => `${row.employee.first_name} ${row.employee.last_name ?? ''}`,
    },
];

// Componente principal de la tabla
export function ReservasTable() {
    const [data, setData] = useState<ReservaItem[]>([]);

    useEffect(() => {
        async function fetchData() {
            const res = await fetch('/api/reserva', {
                credentials: 'include',
            });
            const json = await res.json();
            setData(json.reservations);
        }
        fetchData();
    }, []);

    const table = useReactTable({
        data,
        columns,
        getCoreRowModel: getCoreRowModel(),
    });

    return (
        <div className="bg-background">
            <Table>
                <TableHeader>
                    {table.getHeaderGroups().map((headerGroup) => (
                        <TableRow key={headerGroup.id} className="hover:bg-transparent">
                            {headerGroup.headers.map((header) => (
                                <TableHead key={header.id}>
                                    {header.isPlaceholder ? null : flexRender(header.column.columnDef.header, header.getContext())}
                                </TableHead>
                            ))}
                        </TableRow>
                    ))}
                </TableHeader>
                <TableBody>
                    {table.getRowModel().rows?.length ? (
                        table.getRowModel().rows.map((row) => (
                            <TableRow key={row.id} data-state={row.getIsSelected() && 'selected'}>
                                {row.getVisibleCells().map((cell) => (
                                    <TableCell key={cell.id}>{flexRender(cell.column.columnDef.cell, cell.getContext())}</TableCell>
                                ))}
                            </TableRow>
                        ))
                    ) : (
                        <TableRow>
                            <TableCell colSpan={columns.length} className="h-24 text-center">
                                Aún no existen registros.
                            </TableCell>
                        </TableRow>
                    )}
                </TableBody>
            </Table>
        </div>
    );
}
