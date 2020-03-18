<html lang="en" style="height: auto;">
    <head>
        <title>Surat Permohonan Assessment</title>
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
<body class="" dir="ltr"  style="height: auto; margin : 3 px;">

<div class="container">
<div class="row">
<div class="displayInline100">
    <img class="imgClassHeader1" src="https://lspenergi-staging.sertimedia.com/static/media/Sertimedia_Logo.e350d351.png" width="150px" height="150px" />
</div>
<br>
<div class= "displayinline">
<div  style="text-align:right; margin-top:10px" >Jakarta, <?=date("d F Y")?></div>
</div>
<div style="display:inline-block; margin-top:10px; width:100%">
<table class="tbl_info">
    <tbody>
        <tr>
            <td>Nomor</td> <td>:</td> <td>01/TUK<?=$tuk->tuk_name?>/X/<?=date("Y")?></td>
        </tr>
             <tr>
            <td>Lampiran</td> <td>:</td> <td>-</td>
        </tr>
        <tr>
            <td>Perihal</td> <td>:</td> <td><b>Permohonan Assessment</b></td>
        </tr>

    </tbody>
</table>
</div>

<div style="display:inline-block; margin-top:20px; width:100%">
<table class="tbl_info">
    <tbody>
        <tr>
            <td>Kepada Yth,</td> 
        </tr>
        <tr>
            <td><b> Kepada Ketua LSP Energi</b></td> 
        </tr>
                <tr>
            <td>Ditempat.</td> 
        </tr>
    </tbody>
</table>
</div>
<div  style="text-align:left; margin-top:20px"> Dengan Hormat,</div>
<div class= "displayinline">
<div  class="text_content"style="margin-top:10px">
Bersama ini kami sampaikan bahwa Tempat Uji Kompetensi <b><?=ucwords($tuk->tuk_name)?></b> akan mengadakan Asesmen yang akan kami laksanakan pada :
</div>
</div>

<div style="display:inline-block;  width:100%">
<table class="tbl_info">
    <tbody style="text-indent: 60px">
        <tr>
             <td>Hari/Tanggal</td> <td>:</td> <td>Kamis/ <?=date("j", strtotime($assessment->start_date))?> <?=date("F", strtotime($assessment->start_date))?> <?=date("Y", strtotime($assessment->start_date))?></td>
        </tr>
        <tr>
             <td>Waktu</td> <td>:</td> <td>Jam 08.00 sd selesai</td>
        </tr>
        <tr>
           <td >Tempat</td> <td>:</td> <td >
               <?=$assessment->address?> 
           </td>
        </tr>
        <?php if (!empty($applicant["count"])) : ?>
        <tr>
             <td>Peserta&nbspAsesmen</td> <td>:</td> <td><?=$applicant["count"]?> Orang Peserta</td>
         </tr>
        <?php endif;?>
    </tbody>
</table>
</div>

<div class= "displayinline">
<div  class="text_content"style="margin-top:20px">
Berkenaan dengan hal tersebut diatas, dengan ini kami memohon dapat diberikan kelancaran dan juga dipersiapkan bahan perangkat dan Asesornya guna pelaksanaan Asesmen dimaksud.
</div>
</div>
<div  style="text-align:left; margin-top:20px"> Demikian kami sampaika, atas perhatian dan kerjasamanya kami ucapkan terimakasih.</div>

                <div style="display:inline-block; margin-top:20px; width:100%">
                    <div style="display:inline-block; vertical-align:top; width:63%">
                        
                    </div>
                    <div style="display:inline-block; vertical-align:top; width:35%">
                        <div class="textCenter" style="margin-top:20px !important">Hormat Kami</div>
                        <?php if (!empty($signature["media"])): ?>
                            <div class="textCenter" style="margin-top:20px !important">
                                <img src="data:<?=$signature["mime_type"]?>;base64,<?=$signature["media"]?>" width="100px" height="100px"/>
                            </div>
                        <?php endif; ?>
                        <div>
                            <div class="textCenter"><u> Sutoto Agus Basuki </u></br> Ketua TUK <?=$tuk->tuk_name?></div>
                        </div>
                    </div>
                </div>
 <div class= "displayinline"style="text-align: center; margin-top:250px">
</html>