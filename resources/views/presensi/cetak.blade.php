{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>A4</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        @page {
            size: A5
        }

        * {
            font-family: sans-serif;
        }

        .user-photo {
            float: left;
            margin-right: 20px;
        }

        .user-photo img {
            max-width: 100px;
            max-height: 100px;
            border: 1px solid #ccc;
        }

        .user-info {
            overflow: hidden;
        }

        .user-info div {
            margin-bottom: 10px;
        }

        .avatar {
            max-width: 50px;
            max-height: 50px;
        }

        .right-align {
            text-align: right;
        }
        
    </style>
</head>

<body class="A4">
    @php
        function selisih($jam_masuk, $jam_keluar)
        {
            [$h, $m, $s] = explode(':', $jam_masuk);
            $dtAwal = mktime($h, $m, $s, '1', '1', '1');
            [$h, $m, $s] = explode(':', $jam_keluar);
            $dtAkhir = mktime($h, $m, $s, '1', '1', '1');
            $dtSelisih = $dtAkhir - $dtAwal;
            $totalmenit = $dtSelisih / 60;
            $jam = explode('.', $totalmenit / 60);
            $sisamenit = $totalmenit / 60 - $jam[0];
            $sisamenit2 = $sisamenit * 60;
            $jml_jam = $jam[0];
            return $jml_jam . ':' . round($sisamenit2);
        }
    @endphp
    <section class="sheet padding-10mm">
        <div class="container">
            <div class="row">
                <div class="col-2">
                    <img src="{{ asset('assets/img/logos/imm.png') }}" alt="Logo" class="img-thumbnail border-0">
                </div>
                <div class="col-10">
                    <h4 class="fs-4">
                        IMM (Indonesia Mitra Media)<br>
                        Laporan Presensi Pegawai {{ $user->name }}<br>
                        Periode {{ $namaBulan[$bulan] }} {{ $tahun }}
                    </h4>
                </div>
            </div>
        </div>
        <hr>
        <div class="dataUser">
            <div class="user-photo">
                @php
                    $path = 'uploads/users/' . $user->photo;
                @endphp

                @if (file_exists(public_path($path)))
                    <img src="{{ asset($path) }}" alt="User Photo" class="img-fluid border-0"
                        style="max-width: 100px; max-height: 100px;">
                @else
                    <img src="{{ asset('assets/img/no-image.png') }}" alt="No Image" class="img-thumbnail border-0"
                        style="max-width: 100px; max-height: 100px;">
                @endif
            </div>
            <div class="user-info">
                <table>
                    <tr>
                        <td><b>Nama Pegawai</b></td>
                        <td style="padding-left: 10px;"><b>:</b></td>
                        <td style="padding-left: 10px;">{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <td><b>Jabatan</b></td>
                        <td style="padding-left: 10px;"><b>:</b></td>
                        <td style="padding-left: 10px;">{{ $user->position }}</td>
                    </tr>
                    <tr>
                        <td><b>Telepon</b></td>
                        <td style="padding-left: 10px;"><b>:</b></td>
                        <td style="padding-left: 10px;">{{ $user->no_hp }}</td>
                    </tr>
                </table>
            </div>
        </div><br><br>

        <table class="table table-striped table-hover">
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Jam Masuk</th>
                <th>Foto</th>
                <th>Jam Pulang</th>
                <th>Foto</th>
                <th>Keterangan</th>
                <th>Jml Jam</th>
            </tr>
            @foreach ($presensi as $p)
                @php
                    $masuk = Storage::url('public/uploads/absensi/' . $p->photo_in);
                    $keluar = Storage::url('public/uploads/absensi/' . $p->photo_out);
                    $path = Storage::url('public/uploads/no-image/no-image.png');
                @endphp
                <tr style="font-size: 13px;">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $p->attendance_date }}</td>
                    <td>{{ $p->check_in_time }}</td>
                    <td>
                        @if ($p->photo_in)
                            <img src="{{ url($masuk) }}" alt="" class="avatar">
                        @else
                            <img src="{{ url($path) }}" alt="" class="avatar">
                        @endif
                    </td>
                    <td>{!! $p->check_out_time != null ? $p->check_out_time : '<span class="badge bg-danger">Belum Absen</span>' !!}</td>
                    <td>
                        @if ($p->check_out_time != null)
                            <img src="{{ url($keluar) }}" alt="" class="avatar">
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="icon icon-tabler icon-tabler-exclamation-circle" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"></path>
                                <path d="M12 9v4"></path>
                                <path d="M12 16v.01"></path>
                            </svg>
                        @endif
                    </td>
                    <td>
                        @if ($p->check_in_time >= '11:00')
                            @php
                                $jam_terlambat = selisih('11:00:00', $p->check_in_time);
                            @endphp
                            <span class="badge bg-danger">Terlambat {{ $jam_terlambat }}</span>
                        @else
                            <span class="badge bg-success">Tepat Waktu</span>
                        @endif
                    </td>
                    <td>
                        @if ($p->check_out_time != null)
                            @php
                                $jmljamkerja = selisih($p->check_in_time, $p->check_out_time);
                                [$jam, $menit] = explode(':', $jmljamkerja); // Memisahkan jam dan menit

                                $jamText = $jam > 1 ? 'jam' : 'jam'; // Plural atau singular "jam"
                                $menitText = $menit > 1 ? 'menit' : 'menit'; // Plural atau singular "menit"

                                $jmljamkerja = $jam . ' ' . $jamText . ' ' . $menit . ' ' . $menitText;
                            @endphp
                        @else
                            @php
                                $jmljamkerja = 'Tidak Pulang';
                            @endphp
                        @endif

                        {{ $jmljamkerja }}
                    </td>
                </tr>
            @endforeach
        </table>
        <table class="footer" width="100%" style="margin-top: 390px;">
            <tr>
                <td colspan="2" style="text-align: right; height: 60px;">
                    Kebumen, {{ date('d-m-Y') }}
                </td>
            </tr>
            <tr>
                <td style="text-align: center; vertical-align: bottom; padding-right: 90px" height="150px">
                    <u>Dimas Kenzo</u><br>
                    <i><b>HRD Manager</b></i>
                </td>
                <td style="text-align: center; vertical-align: bottom;">
                    <u>Dolorem</u><br>
                    <i><b>Direktur</b></i>
                </td>
            </tr>
        </table>
    </section>
</body>

</html>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
</script> --}}


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Presensi Pegawai {{ $user->name }}</title>
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
    @php
        function selisih($jam_masuk, $jam_keluar)
        {
            [$h, $m, $s] = explode(':', $jam_masuk);
            $dtAwal = mktime($h, $m, $s, '1', '1', '1');
            [$h, $m, $s] = explode(':', $jam_keluar);
            $dtAkhir = mktime($h, $m, $s, '1', '1', '1');
            $dtSelisih = $dtAkhir - $dtAwal;
            $totalmenit = $dtSelisih / 60;
            $jam = explode('.', $totalmenit / 60);
            $sisamenit = $totalmenit / 60 - $jam[0];
            $sisamenit2 = $sisamenit * 60;
            $jml_jam = $jam[0];
            return $jml_jam . ':' . round($sisamenit2);
        }
    @endphp
    <section class="py-20 bg-slate-300">
        <div class="max-w-5xl mx-auto py-16 bg-white">
            <article class="overflow-hidden">
                <div class="bg-[white] rounded-b-md">
                    <div class="p-9">
                        <div class="space-y-6 text-slate-700">
                            <img class="object-cover h-12" src="{{ asset('assets/img/logos/imm.png') }}" />
                            <p class="text-xl font-extrabold tracking-tight uppercase font-body">
                                IMM (Indonesia Mitra Media) <br>
                                Laporan Presensi Pegawai {{ $user->name }}<br>
                                Periode {{ $namaBulan[$bulan] }} {{ $tahun }}
                            </p>
                        </div>
                    </div>
                    <div class="p-9">
                        <div class="flex w-full">
                            <div class="grid pr-7">
                                <img src="{{ asset('storage/uploads/users/3305081234560001.jpg') }}" alt="" class="avatar2">
                            </div>
                            <div class="grid grid-cols-4 gap-12">
                                <div class="text-xl font-light text-slate-500">
                                    <h3 class="font-normal text-slate-700">
                                        Data Pegawai:
                                    </h3>
                                    <p class="text-md">{{ $user->name }}</p>
                                    <p class="text-md">{{ $user->position }}</p>
                                    <p class="text-md">{{ $user->no_hp }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-9">
                        <div class="flex flex-col mx-0">
                            <table class="min-w-full divide-y divide-slate-500">
                                <thead>
                                    <tr>
                                        <th scope="col"
                                            class="py-3.5 pl-4 text-left text-sm font-normal text-slate-700 sm:pl-6 md:pl-0">
                                            No
                                        </th>
                                        <th scope="col"
                                            class="py-3.5 pl-4 text-left text-sm font-normal text-slate-700 sm:pl-6 md:pl-0">
                                            Jam Masuk
                                        </th>
                                        <th scope="col"
                                            class="py-3.5 pl-4 text-left text-sm font-normal text-slate-700 sm:pl-6 md:pl-0">
                                            Tanggal
                                        </th>
                                        <th scope="col"
                                            class="py-3.5 pl-4 text-left text-sm font-normal text-slate-700 sm:pl-6 md:pl-0">
                                            Foto
                                        </th>
                                        <th scope="col"
                                            class="py-3.5 pl-4 text-left text-sm font-normal text-slate-700 sm:pl-6 md:pl-0">
                                            Jam Pulang
                                        </th>
                                        <th scope="col"
                                            class="py-3.5 pl-4 text-left text-sm font-normal text-slate-700 sm:pl-6 md:pl-0">
                                            Foto
                                        </th>
                                        <th scope="col"
                                            class="py-3.5 pl-4 text-left text-sm font-normal text-slate-700 sm:pl-6 md:pl-0">
                                            Keterangan
                                        </th>
                                        <th scope="col"
                                            class="py-3.5 pl-4 text-left text-sm font-normal text-slate-700 sm:pl-6 md:pl-0">
                                            Jml Jam
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($presensi as $p)
                                        @php
                                            $masuk = Storage::url('public/uploads/absensi/' . $p->photo_in);
                                            $keluar = Storage::url('public/uploads/absensi/' . $p->photo_out);
                                            $path = Storage::url('public/uploads/no-image/no-image.png');
                                        @endphp
                                        <tr class="text-sm font-normal">
                                            <td class="py-4">{{ $loop->iteration }}</td>
                                            <td class="py-4">{{ $p->attendance_date }}</td>
                                            <td class="py-4">{{ $p->check_in_time }}</td>
                                            <td class="py-4">
                                                @if ($p->photo_in)
                                                    <img src="{{ url($masuk) }}" alt="" class="avatar">
                                                @else
                                                    <img src="{{ url($path) }}" alt="" class="avatar">
                                                @endif
                                            </td>
                                            <td class="py-4">{!! $p->check_out_time != null ? $p->check_out_time : '<span class="badge bg-danger">Belum Absen</span>' !!}</td>
                                            <td class="py-4">
                                                @if ($p->check_out_time != null)
                                                    <img src="{{ url($keluar) }}" alt="" class="avatar">
                                                @else
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="icon icon-tabler icon-tabler-exclamation-circle"
                                                        width="24" height="24" viewBox="0 0 24 24"
                                                        stroke-width="2" stroke="currentColor" fill="none"
                                                        stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                        <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"></path>
                                                        <path d="M12 9v4"></path>
                                                        <path d="M12 16v.01"></path>
                                                    </svg>
                                                @endif
                                            </td>
                                            <td class="py-4">
                                                @if ($p->check_in_time >= '11:00')
                                                    @php
                                                        $jam_terlambat = selisih('11:00:00', $p->check_in_time);
                                                    @endphp
                                                    <span class="bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-1.5 rounded dark:bg-red-900 dark:text-red-300">Terlambat {{ $jam_terlambat }}</span>
                                                @else
                                                    <span class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-1.5 rounded dark:bg-green-900 dark:text-green-300">Tepat Waktu</span>
                                                @endif
                                            </td>
                                            <td class="py-2">
                                                @if ($p->check_out_time != null)
                                                    @php
                                                        $jmljamkerja = selisih($p->check_in_time, $p->check_out_time);
                                                        [$jam, $menit] = explode(':', $jmljamkerja); // Memisahkan jam dan menit
                                            
                                                        $jamText = $jam > 1 ? 'jam' : 'jam'; // Plural atau singular "jam"
                                                        $menitText = $menit > 1 ? 'menit' : 'menit'; // Plural atau singular "menit"
                                            
                                                        $jmljamkerja = $jam . ' ' . $jamText . ' ' . $menit . ' ' . $menitText;
                                                    @endphp
                                            
                                                    @if ($jam > 0 || $menit > 0)
                                                        <span class="bg-red-100 text-red-800 text-xs font-medium mr-1 px-1.5 py-1.5 rounded dark:bg-red-900 dark:text-red-300">Terlambat {{ $jmljamkerja }}</span>
                                                    @else
                                                        <span class="bg-red-100 text-red-800 text-xs font-medium mr-1 px-1 py-1.5 rounded dark:bg-red-900 dark:text-red-300">{{ $jmljamkerja }}</span>
                                                    @endif
                                                @else
                                                    @php
                                                        $jmljamkerja = 'Tidak Pulang';
                                                    @endphp
                                                    <span class="bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-1.5 rounded dark:bg-red-900 dark:text-red-300">{{ $jmljamkerja }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                {{-- <tfoot>
                                    <tr>
                                        @foreach ($presensi as $p)
                                        <th scope="row" colspan="3"
                                            class="hidden pt-6 pl-6 pr-3 text-sm font-light text-right text-slate-500 sm:table-cell md:pl-0">
                                            {{ $p->attendance_date }}
                                        </th>
                                        <th scope="row"
                                            class="pt-6 pl-4 pr-3 text-sm font-light text-left text-slate-500 sm:hidden">
                                            Subtotal
                                        </th>
                                        <td class="pt-6 pl-3 pr-4 text-sm text-right text-slate-500 sm:pr-6 md:pr-0">
                                            $0.00
                                        </td>
                                        @endforeach

                                    </tr>
                                    <tr>
                                        <th scope="row" colspan="3"
                                            class="hidden pt-6 pl-6 pr-3 text-sm font-light text-right text-slate-500 sm:table-cell md:pl-0">
                                            Discount
                                        </th>
                                        <th scope="row"
                                            class="pt-6 pl-4 pr-3 text-sm font-light text-left text-slate-500 sm:hidden">
                                            Discount
                                        </th>
                                        <td class="pt-6 pl-3 pr-4 text-sm text-right text-slate-500 sm:pr-6 md:pr-0">
                                            $0.00
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row" colspan="3"
                                            class="hidden pt-4 pl-6 pr-3 text-sm font-light text-right text-slate-500 sm:table-cell md:pl-0">
                                            Tax
                                        </th>
                                        <th scope="row"
                                            class="pt-4 pl-4 pr-3 text-sm font-light text-left text-slate-500 sm:hidden">
                                            Tax
                                        </th>
                                        <td class="pt-4 pl-3 pr-4 text-sm text-right text-slate-500 sm:pr-6 md:pr-0">
                                            $0.00
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row" colspan="3"
                                            class="hidden pt-4 pl-6 pr-3 text-sm font-normal text-right text-slate-700 sm:table-cell md:pl-0">
                                            Total
                                        </th>
                                        <th scope="row"
                                            class="pt-4 pl-4 pr-3 text-sm font-normal text-left text-slate-700 sm:hidden">
                                            Total
                                        </th>
                                        <td
                                            class="pt-4 pl-3 pr-4 text-sm font-normal text-right text-slate-700 sm:pr-6 md:pr-0">
                                            $0.00
                                        </td>
                                    </tr>
                                </tfoot> --}}
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
