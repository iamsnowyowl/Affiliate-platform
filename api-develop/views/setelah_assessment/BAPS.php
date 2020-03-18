<html lang="en" style="height: auto;">
    <head>
        <title>Berita Acara Penerbitan Sertifikat</title>
        <meta charset="utf-8">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="/assets/css/style.css">
        <title>Document</title>
        <style>
            *{
                font-size:14px;
            }
        </style>
    </head>
    <body style="height: auto;">
        <div class="container">
            <div class="row">
                <table class="tableHeaderFooter">
                    <thead>
                        <tr>
                            <td>
                                <div class="displayInline100 divheader">
                                    <img src="/files/logo.png" width="70px" height="70px" />
                                </div>
                            </td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="displayInline100">
                                    <div class="header1-nomer"><h3 class="fontWeight-700" style="margin:0;margin-top:20px;text-align: center">BERITA ACARA PENERBITAN SERTIFIKAT</h3></div>
                                </div>

                                <div class="displayInline100">
                                    <div class="header1-nomer"><h4 style="margin:0;margin-top:5px;"><?=$assessment->letter_number?></h4></div>
                                </div>

                                <div style="display:inline-block; margin-top:20px; width:100%">
                                    Yang bertanda tangan di bawah ini, Panitia Teknis Sertifikasi LSP Energi dengan nomor surat tugas <?=$assessment->letter_number?>/LSPE/ST/XI/<?=$assessment->year?>, 
                                    setelah verifikasi atas bukti-bukti dan dokumen asesmen yang dilaksanakan:
                                </div>

                                <div class="displayInline100">
                                    <table class="table_info3-1 border-all paddingTd-10" style="margin-top:20px;">
                                        <tbody>
                                            <tr>
                                                <td style="background: #d4d4ce">Tanggal</td>
                                                <td><?=date("j", strtotime($assessment->pleno_date))?> <?=date("F", strtotime($assessment->pleno_date))?> <?=date("Y", strtotime($assessment->pleno_date))?> </td>
                                            </tr>
                                            <tr>
                                                <td style="background: #d4d4ce">Bertempat di:</td>
                                                <td>Kantor LSP Energi, Bekasi</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div style="display:inline-block; margin-top:20px; width:100%">
                                    Dengan ini memberikan rekomendasi keputusan sertifikasi sebagai berikut:
                                </div>

                                
                                <div class="displayInline100">
                                    <table class="table_info3-1 border-all paddingTd-10" style="margin-top:20px;">
                                        <tbody>
                                            <tr>
                                                <td style="background: #d4d4ce">Nama Asesor :</td>
                                                <td><?php
                                                    $names = array();
                                                    foreach ($assessor as $key => $value) {
                                                        $names[] = ucwords($value->first_name." ".$value->last_name);
                                                    }
                                                    echo implode(", ", $names);
                                                ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="background: #d4d4ce">Tanggal Asesmen:</td>
                                                <td><?=date("j", strtotime($assessment->start_date))?> <?=date("F", strtotime($assessment->start_date))?> <?=date("Y", strtotime($assessment->start_date))?></td>
                                            </tr>
                                            <tr>
                                                <td style="background: #d4d4ce">Tempat Asesmen:</td>
                                                <td><?=$tuk->tuk_name?>, <?=$tuk->address?>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div style="display:inline-block; margin-top:20px; width:100%">
                                    Ket: Berikan tanda pada kolom yang tersedia, apabila kompeten berikan tanda “&#10004;” dan jika belum kompeten berikan tanda “x”
                                </div>

                                
                                <div class="displayInline100">
                                    <table class="table_info3-1 border-all paddingTd-10 paddingTh-10 Td1-50 headerCenter" style="margin-top:20px;">
                                        <thead>
                                            <tr>
                                                <th style="background: #d4d4ce">No.</th>
                                                <th style="background: #d4d4ce">Nama Kandidat</th>
                                                <th style="background: #d4d4ce">Cluster/Unit Kompetensi</th>
                                                <th style="background: #d4d4ce">Pantek</th>
                                                <th style="background: #d4d4ce">Keputusan LSP Energi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($applicant as $key => $value): ?>
                                            <tr>
                                                <td style="background: #d4d4ce"><?=$key+1?></td>
                                                <td><?=ucwords($value->first_name." ".$value->last_name)?></td>
                                                <td><?=ucwords($value->schema_label)?> </td>
                                                <td>
                                                    <?php if ($value->status_graduation == "L"): ?>
                                                        &#10004;
                                                    <?php endif;?>
                                                    <?php if ($value->status_graduation == "TL"): ?>
                                                        &#10007;
                                                    <?php endif;?>
                                                </td>
                                                <td>
                                                    <?php if ($value->status_graduation == "L"): ?>
                                                        KOMPETEN
                                                    <?php endif;?>
                                                    <?php if ($value->status_graduation == "TL"): ?>
                                                        TIDAK KOMPETEN
                                                    <?php endif;?>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- <div class="pageBreakBefore"></div> -->


                                <div class="displayInline100">
                                    <div class="header1-nomer"><h4 style="margin:0;margin-top:20px;text-align: center">Bekasi, <?=date("j", strtotime($assessment->pleno_date))?> <?=date("F", strtotime($assessment->pleno_date))?> <?=date("Y", strtotime($assessment->pleno_date))?></h4></div>
                                </div>
                                <div class="displayInline100">
                                    <div class="header1-nomer"><h4 style="margin:0;margin-top:5px;text-align: center">Diperiksa oleh,</h4></div>
                                </div>

                                
                                <div class="justify-content-center" style="display:inline-block; width:100%">
                                    <?php
                                        $pleno_member = array();
                                        
                                        foreach ($pleno as $key => $value) {
                                            $pleno_member[$value->position] = $value;
                                        }
                                    ?>
                                    <?php if (!empty($pleno_member["ANGGOTA_1"])) :?>
                                        <div style="display:inline-block; vertical-align:top; width:32%">
                                            <div class="textCenter"> <img src="<?=$pleno_member["ANGGOTA_1"]->signature?>" widht="100px" height="100px"> </div>
                                            <div class="textCenter" style="text-decoration: underline"> (<?=ucwords($pleno_member["ANGGOTA_1"]->first_name." ".$pleno_member["ANGGOTA_1"]->last_name)?>) </div>
                                            <div class="textCenter" style="margin-top:10px !important">ANGGOTA 1</div>
                                        </div>
                                    <?php else:?>
                                        <div style="display:inline-block; vertical-align:top; width:32%">
                                            <div class="textCenter" style="margin-top:100px !important"> (________________) </div>
                                            <div class="textCenter" style="margin-top:10px !important">ANGGOTA 1</div>
                                        </div>
                                    <?php endif;?>

                                    <?php if (!empty($pleno_member["KETUA"])) :?>
                                        <div style="display:inline-block; vertical-align:top; width:32%">
                                            <div class="textCenter"> <img src="<?=$pleno_member["KETUA"]->signature?>" widht="100px" height="100px"> </div>
                                            <div class="textCenter" style="text-decoration: underline"> (<?=ucwords($pleno_member["KETUA"]->first_name." ".$pleno_member["KETUA"]->last_name)?>) </div>
                                            <div class="textCenter" style="margin-top:10px !important">KETUA PANITIA TEKNIS</div>
                                        </div>
                                    <?php else:?>
                                        <div style="display:inline-block; vertical-align:top; width:32%">
                                            <div class="textCenter" style="margin-top:100px !important"> (________________) </div>
                                            <div class="textCenter" style="margin-top:10px !important">KETUA PANITIA TEKNIS</div>
                                        </div>
                                    <?php endif;?>

                                    <?php if (!empty($pleno_member["ANGGOTA_2"])) :?>
                                        <div style="display:inline-block; vertical-align:top; width:32%">
                                            <div class="textCenter"> <img src="<?=$pleno_member["ANGGOTA_2"]->signature?>" widht="100px" height="100px"> </div>
                                            <div class="textCenter" style="text-decoration: underline"> (<?=ucwords($pleno_member["ANGGOTA_2"]->first_name." ".$pleno_member["ANGGOTA_2"]->last_name)?>) </div>
                                            <div class="textCenter" style="margin-top:10px !important">ANGGOTA 2</div>
                                        </div>
                                    <?php else:?>
                                        <div style="display:inline-block; vertical-align:top; width:32%">
                                            <div class="textCenter" style="margin-top:100px !important"> (________________) </div>
                                            <div class="textCenter" style="margin-top:10px !important">ANGGOTA 2</div>
                                        </div>
                                    <?php endif;?>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                        <tr>
                            <td>
                                <div class="displayInline100" style="margin-top:20px;width:800px;">
                                    <table class="table_info1 fontSizeAll-14">
                                        <tbody>
                                            <tr>
                                                <td style="width:250px;">Komplek Grand Galaxy Park</td>
                                                <td class="empty"></td>
                                                <td>E: info@lspenergi.com </td>
                                            </tr>
                                            <tr>
                                                <td style="width:250px;">Blok RSK 6 No. 10 Bekasi</td>
                                                <td class="empty"></td>
                                                <td>P: (021) 22103604 </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>
                </table>
            </div>
        </div>
    </body>
</html>