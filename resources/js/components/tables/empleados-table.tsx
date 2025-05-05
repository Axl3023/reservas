'use client';

import { Checkbox } from '@/components/ui/checkbox';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { ColumnDef, flexRender, getCoreRowModel, useReactTable } from '@tanstack/react-table';
import { useEffect, useState } from 'react';

type EmpleadoItem = {
    id: number;
    first_name: string;
    last_name: string;
    email: string;
    phone: string;
    position: string;
};

// Columnas de la tabla
const columns: ColumnDef<EmpleadoItem>[] = [
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
        header: 'Nombre',
        accessorKey: 'first_name',
    },
    {
        header: 'Apellido',
        accessorKey: 'last_name',
    },
    {
        header: 'Email',
        accessorKey: 'email',
    },
    {
        header: 'Teléfono',
        accessorKey: 'phone',
    },
    {
        header: 'Posición',
        accessorKey: 'position',
    },
];

// Componente principal de la tabla
export function EmpleadosTable() {
    const [data, setData] = useState<EmpleadoItem[]>([]);

    useEffect(() => {
        async function fetchData() {
            const res = await fetch('');
            const json = await res.json();
            setData(json.slice(0, 5)); // 5 primeros elementos
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
