<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Nuevo mensaje</title>
</head>
<body>
    <h1>Nuevo mensaje del dispositivo {{$device->name}} </h1>

    <P>Mensaje: <b>{{$content}}</b> </P>
    <br>
    <p>Fecha:  <b>{{$mensaje->time}}</b></p>
    <p>Seq num:  <b>{{$mensaje->seq_num}}</b></p>
    <p>Data:  <b>{{$mensaje->data}}</b></p>
    <p>Device type id:  <b>{{$mensaje->device_type_id}}</b></p>
</body>
</html>