<?php
use Illuminate\Support\Facades\URL ;
?>
<html>
    <head>
        <!-- Jquery -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

        <!-- Bootstrap -->
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

        <!-- Select2 -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    </head>
    <body style='' >
        <div style="height: 15px;background: #f4645f;" ></div>
        <nav class="navbar navbar-default">
            <ul class="nav navbar-nav">
                <li><a href="{{url('laracrud')}}">Home</a></li>
                <li><a href="{{url('laracrud/model')}}">Model Generator</a></li>
                <li><a href="{{url('laracrud/crud')}}">CRUD Generator</a></li>
            </ul>
        </nav>
        @yield('content')
    </body>

    <script>
        $('.select2').select2()
    </script>
    
    <script type="text/javascript">
        $(document).ready(function () {
            
            $('nav li a').each(function(){
                if($(this).attr('href') == '{{URL::current()}}' )
                    $(this).parents('li').addClass('active');
            })
         
        });


    </script> 
</html>