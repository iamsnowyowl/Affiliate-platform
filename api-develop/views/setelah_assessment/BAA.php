<html lang="en" style="height: auto;">
    <head>
        <title>Berita Acara Assessor</title>
        <meta charset="utf-8">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="/assets/css/style.css">
        <title>Berita Acara Assessor</title>
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
                                    <img src="/files/logo.png" width="70px" height="70px"/>
                                </div>
                            </td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="displayInline100">
                                    <div class="header1-nomer"><h3 class="fontWeight-700" style="margin:0;margin-top:5px;text-align: center">BERITA ACARA ASESMEN/UJI KOMPETENSI LSP ENERGI</h3></div>
                                    <div class="header1-nomer"><h3 class="fontWeight-700" style="margin:0;margin-top:5px;text-align: center">PELAKSANAAN SERTIFIKASI TAHUN <?=date("Y")?></h3></div>
                                    <div class="header1-nomer"><h3 class="fontWeight-700" style="margin:0;margin-top:5px;text-align: center">LSP ENERGI</h3></div>
                                </div>
                                <div class="displaInline100">
                                    <?php
                                        $schema = array();
                                        if (!empty($applicant))
                                        {
                                            for ($i=0; $i < count($applicant); $i++) { 
                                                $schema[$applicant[$i]->sub_schema_number] = $applicant[$i]->schema_label."(".$applicant[$i]->sub_schema_number.")";
                                            }
                                        }
                                    ?>
                                    Pada hari ini tanggal <?=strftime("%e", strtotime($assessment->end_date))?> Bulan <?=strftime("%B", strtotime($assessment->end_date))?> Tahun <?=strftime("%Y", strtotime($assessment->end_date))?>, bertempat di TUK <?=$tuk->address?> telah dilakukan Uji Kompetensi
                                    Skema  <?=implode(", ", $schema)?>
                                    yang diikuti sebanyak <?=count($applicant)?> peserta dengan penjelasan sebagai berikut:
                                </div>
                                <div class="displaInline100">
                                    Asesor:
                                </div>

                                <div class="displayInline100">
                                    <table class="table_info3-1 paddingTd-10 paddingTh-10 Td1-50" style="margin-top:20px;">
                                        <tbody>
                                            <?php foreach ($assessor as $key => $value) : ?>
                                            <tr>
                                                <td style="text-align:left"><?=$key+1?></td>
                                                <td style="text-align:left"><?=ucwords($value->first_name." ".$value->last_name)?></td>
                                                <td style="text-align:left"><?=$value->registration_number?></td>
                                                <td style="text-align:left"></td>
                                            </tr>
                                        <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>


                                <div class="displayInline100">
                                    <table class="table_info3-1 border-all paddingTd-10 paddingTh-10 Td1-50 headerCenter" style="margin-top:20px;">
                                        <thead>
                                            <tr>
                                                <th rowspan="2" style="background: #d4d4ce">No</th>
                                                <th rowspan="2" style="background: #d4d4ce">Nama</th>
                                                <th rowspan="2" style="background: #d4d4ce;">Organisasi</th>
                                                <th colspan="2" style="background: #d4d4ce">Rekomendasi</th>
                                            </tr>
                                            <tr>
                                                <th style="background: #d4d4ce">K</th>
                                                <th style="background: #d4d4ce">BK</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($applicant as $key => $value) :?>
                                            <tr>
                                                <td style="text-align:left"><?=$key+1?></td>
                                                <td style="text-align:center"><?=ucwords($value->first_name." ".$value->last_name)?></td>
                                                <td style="text-align:center"><?=ucwords($value->institution)?></td>
                                                <td style="text-align:left"><?=(($value->status_recomendation == "K") ? "&#10004" : "")?></td>
                                                <td style="text-align:left"><?=(($value->status_recomendation == "BK") ? "&#10004" : "")?></td>
                                            </tr>
                                            <?php endforeach;?>
                                            
                                        </tbody>
                                    </table>
                                </div>

                                <div class="displayInline100" style="margin-top:10px;">
                                    Demikian berita acara Asesmen/uji kompetensi dibuat untuk sebagai pengambilan keputusan oleh tim Asesor LSP Energi
                                </div>

                                <div style="display:inline-block; width:100%;margin-top:20px;">
                                    <div style="display:inline-block; vertical-align:top; width:68%">
                                    </div>
                                    <div style="display:inline-block; vertical-align:top; width:30%">
                                        <div style="margin-top:10px !important;text-align: right;"><?=strftime("%e %B %Y", strtotime($assessment->start_date))?></div>
                                        <div style="margin-top:10px !important">Asesor Kompetensi</div>
                                        <div style="margin-top:10px !important">1............................... 1.....................</div>
                                        <div style="margin-top:10px !important">2............................... 3.....................</div>
                                        <div style="margin-top:10px !important">2............................... 3.....................</div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
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
                    </tfoot>
                </table>
            </div>
        </div>
    </body>
</html>