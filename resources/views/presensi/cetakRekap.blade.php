<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Presensi Pegawai</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .bg-black {
            background-color: #030921;
        }

        .bg-white {
            background-color: #fafbf6;
        }

        .avatar {
            max-width: 50px;
            max-height: 50px;
        }

        .avatar2 {
            max-width: 110px;
            max-height: 190px;
        }
    </style>
</head>

<body>
    <section class="py-20 bg-slate-300">
        <div class="max-w-8xl mx-auto py-16 bg-white">
            <article class="overflow-hidden">
                <div class="bg-[white] rounded-b-md">
                    <div class="p-9">
                        <div class="space-y-6 text-slate-700">
                            <img class="object-cover h-12" src="{{ asset('assets/img/logos/imm.png') }}" />
                            <p class="text-xl font-extrabold tracking-tight uppercase font-body">
                                IMM (Indonesia Mitra Media) <br>
                                Laporan Presensi Pegawai<br>
                                Periode {{ $namaBulan[$bulan] }} {{ $tahun }}
                            </p>
                        </div>
                    </div>


                    <div class="p-9">
                        <div class="flex flex-col mx-0">
                            <table class="min-w-full divide-y divide-slate-50 bg-slate-500">
                                <thead>
                                    <tr>
                                        <th rowspan="2"
                                            class="border-none px-4 py-3.5 pr-4 text-center text-sm font-normal text-slate-700">
                                            No
                                        </th>
                                        <th rowspan="2"
                                            class="border-none px-3 py-3.5 pl-4 text-center text-sm font-normal text-slate-700">
                                            NIK
                                        </th>
                                        <th rowspan="2"
                                            class="border-none px-3 py-3.5 pl-4 text-center text-sm font-normal text-slate-700">
                                            Nama Pegawai
                                        </th>
                                        <th colspan="32"
                                            class="border-none px-3 py-3.5 pl-4 text-center text-sm font-normal text-slate-700">
                                            Tanggal
                                        </th>
                                    </tr>
                                    @for ($i = 0; $i <= 31; $i++)
                                        <th class="border-none px-2 text-sm">{{ $i }}</th>
                                    @endfor
                                </thead>
                                <tbody>
                                    @foreach ($rekap as $p)
                                        <tr class="text-sm font-normal bg-slate-50">
                                            <td class="py-4 text-center text-sm">{{ $loop->iteration }}</td>
                                            <td class="py-4 text-center text-sm">{{ $p->nik }}</td>
                                            <td class="py-4 text-center text-sm">{{ $p->name }}</td>
                                            @for ($i = 0; $i <= 31; $i++)
                                                <?php
                                                $tgl = 'tgl_' . $i;
                                                $hadir = empty($p->$tgl) ? ['', ''] : explode('-', $p->$tgl);
                                                ?>
                                                <td class="py-4 text-center">
                                                    <span
                                                        @if (property_exists($p, $tgl) && $hadir[0] > '07.00') class="bg-red-300 text-slate-800 text-xs font-medium mr-2 px-2.5 py-1.5 rounded dark:bg-red-900 dark:text-red-300" @endif>
                                                        {{ $hadir[0] }}
                                                    </span>
                                                    <br><br>
                                                    <span
                                                        @if (property_exists($p, $tgl) && $hadir[1] < '18.00') class="bg-red-300 text-slate-800 text-xs font-medium mr-2 px-2.5 py-1.5 rounded dark:bg-red-900 dark:text-red-300" @endif>
                                                        {{ $hadir[1] }}
                                                    </span>
                                                </td>
                                            @endfor
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mt-48 p-9">
                        <div class="border-t pt-9 border-slate-200">
                            <div class="text-sm font-light text-slate-700">
                                <p>
                                    {{-- Payment terms are 14 days. Please be aware that according to the
                                    Late Payment of Unwrapped Debts Act 0000, freelancers are
                                    entitled to claim a 00.00 late fee upon non-payment of debts
                                    after this time, at which point a new invoice will be submitted
                                    with the addition of this fee. If payment of the revised invoice
                                    is not received within a further 14 days, additional interest
                                    will be charged to the overdue account and a statutory rate of
                                    8% plus Bank of England base of 0.5%, totalling 8.5%. Parties
                                    cannot contract out of the Act’s provisions. --}}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </article>
        </div>
    </section>
</body>

</html>
