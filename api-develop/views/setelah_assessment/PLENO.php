<html lang="en" style="height: auto;">
    <head>
        <title>HRD</title>
        <meta charset="utf-8">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="/assets/css/style.css">
        <title>Document</title>
        <style>
            *{
                font-size:16px;
            }
        </style>
    </head>
    <body style="height: auto;">
        <div class="container">
            <div class="row">
                <div class="displayInline100">
                    <img src="/files/logo.png" height="70px" width="70px" />
                </div>
                <div class="displayInline100">
                    <div class="header1 borderBottom-3px"><h2 class="fontWeight-700" style="margin:0;margin-top:20px;">SURAT TUGAS</h2></div>
                </div>
            
                <div class="displayInline100">
                    <div class="header1-nomer"><h4 style="margin:0;"><?=$assessment->letter_number?></h4></div>
                </div>
            
                <div class="displayInline100">
                    <div style="margin-top:20px">Ketua Lembaga Sertifikasi Profesi Energi menugaskan kepada:</div>
                </div>
            
                <div class="displayInline100" style="margin-top:20px">
                    <table class="table_info2-1 headerBold border-all">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>NAMA</th>
                                <th>JABATAN</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php for ($i=0; $i < count($pleno); $i++): ?>
                                <tr>
                                    <td><?=$i+1?></td>
                                    <td><?=ucwords($pleno[$i]->first_name.$pleno[$i]->last_name)?></td>
                                    <td><?=$pleno[$i]->position?></td>
                                </tr>
                            <?php endfor; ?>
                            
                        </tbody>
                    </table>
                </div>
                <div class="displayInline100">
                    <div style="margin-top:20px">Untuk melaksanakan Rapat Pleno Asesmen pada: </div>
                </div>

                <div class="displayInline100" style="margin-top:20px">
                    <table class="table_info1-1">
                        <tbody>
                            <tr>
                                <td>Tanggal</td>
                                <td>:</td>
                                <td><?=strftime("%A, %e %B %Y", strtotime($assessment->pleno_date)) ?></td>
                            </tr>
                            <tr>
                                <td>Nama</td>
                                <td>:</td>
                                <td><?=$assessment->title?></td>
                            </tr>
                            <tr>
                                <td>Alamat</td>
                                <td>:</td>
                                <td><?=$assessment->address?></td>
                            </tr>
                            <tr>
                                <td>Telepon/Fax</td>
                                <td>:</td>
                                <td><?=$tuk->contact?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>


                <div style="display:inline-block; margin-top:20px; width:100%">
                    <div style="display:inline-block; vertical-align:top; width:63%">
                        
                    </div>
                    <div style="display:inline-block; vertical-align:top; width:35%">
                        <div class="textCenter" style="margin-top:20px !important">Bekasi, <?=date("j F Y")?></div>
                        <div class="textCenter">
                            <img style="vertical-align: middle; margin-left: 30px;" src="<?=$ketualsp->signature?>" width="100px" height="100px">
                        </div>
                        <div style="margin-top:0px !important; width:100%">
                            <div class="textCenter">( <?=ucwords($ketualsp->first_name." ".$ketualsp->last_name)?> )</div>
                        </div>
                    </div>
                </div>

                <div class="displayInline100 positionBottom" style="margin-top:20px;width:800px;">
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

            </div>
        </div>
    </body>
</html>