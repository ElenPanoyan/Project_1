<!DOCTYPE html>
<html>
<head>
    <title>Link shortener</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css"/>
</head>
<body>

<div class="container">
    <div class="card">
        <div class="card-header" style="text-align: center; background-color: darkolivegreen">
            <a style="color:white" href="{{ route('links') }}"
               target="_blank">CLICK TO EXPORT CSV FILE</a></div>

        <div class="card-body">
            <table class="table table-bordered table-sm">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Date</th>
                    <th>Short Link</th>
                    <th>Link</th>
                    <th>QR Code</th>
                </tr>
                </thead>
                <tbody>
                @foreach($links as $row)
                    <tr>
                        <td>{{ $row->user->name}}</td>
                        <td>{{ $row->user->email}}</td>
                        <td>{{ $row->created_at}}</td>
                        <td><a href="{{ route('shorten.link', $row->code) }}"
                               target="_blank">{{ route('shorten.link', $row->code) }}</a></td>
                        <td>{{ $row->link }}</td>
                        <td>
                            <div class="card-body">
                                <img src="{{url($row->qr_code)}}" alt="Image"/>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

</body>
</html>
