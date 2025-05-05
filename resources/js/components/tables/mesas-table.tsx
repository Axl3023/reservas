'use client';

import { Badge } from '@/components/ui/badge';
import { Checkbox } from '@/components/ui/checkbox';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { cn } from '@/lib/utils';
import { ColumnDef, flexRender, getCoreRowModel, useReactTable } from '@tanstack/react-table';
import { useEffect, useState } from 'react';

// Tipo de dato para las filas
type MesaItem = {
    id: number;
    table_number: string;
    capacity: number;
    location: string;
    status: 'available' | 'reserved' | 'occupied';
};

// Columnas de la tabla
const columns: ColumnDef<MesaItem>[] = [
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
        header: 'Número de Mesa',
        accessorKey: 'table_number',
    },
    {
        header: 'Capacidad',
        accessorKey: 'capacity',
    },
    {
        header: 'Ubicación',
        accessorKey: 'location',
    },
    {
        header: 'Estado',
        accessorKey: 'status',
        cell: ({ row }) => (
            <Badge
                className={cn(
                    row.getValue('status') === 'occupied' && 'bg-destructive text-white',
                    row.getValue('status') === 'available' && 'bg-green-600 text-white',
                    row.getValue('status') === 'reserved' && 'bg-yellow-600 text-white',
                )}
            >
                {row.getValue('status')}
            </Badge>
        ),
    },
];

// Componente principal de la tabla
export function MesaTable() {
    const [data, setData] = useState<MesaItem[]>([]);

    useEffect(() => {
        async function fetchData() {
            const res = await fetch('/api/mesa', {
                credentials: 'include',
            });
            console.log(res);
            const json = await res.json();
            setData(json.tables);
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
