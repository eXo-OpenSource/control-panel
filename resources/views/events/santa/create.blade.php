@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div style="flex: 0 0 400px;max-width: 400px;">
                <div class="drawing-area">
                    <div class="santa">
                        <canvas id="head" resize></canvas>
                        <canvas id="body" resize></canvas>
                        <canvas id="legs" resize></canvas>
                    </div>
                </div>
            </div>
        </div>


        <div class="row justify-content-center">
            <div style="flex: 0 0 400px;max-width: 400px;" class="d-block">
                <div class="row justify-content-center">
                    <form action="{{ route('events.santa.store') }}" method="post">
                        @csrf
                        <input id="svgInput" type="text" name="svg" value="" hidden="">
                        <input id="partInput" type="text" name="part" value="body" hidden="">
                        <button id="submit" type="submit" class="btn btn-primary d-none">Absenden</button>
                    </form>
                    <button id="reset" class="btn btn-danger ml-2">Zurücksetzen</button>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('head')
    <style>
        .drawing-area {
            background: pink;
        }
        .santa {
            display: flex;
            flex-direction: column;
            width: 400px;
            height: 600px;
            background: url(/images/santa.png) no-repeat center;
            background-size: 100%;
            margin: 20px 0;
        }
        #head{
            height: 230px;
        }
        #body{
            height: 190px;
        }
        #legs{
            height: 180px;
        }

        body.body #body, body.legs #legs, body.head #head {
            background: rgba(255, 255, 255, 0.5);
        }
    </style>
@endsection

@section('script')
    <script src="/js/paper.js"></script>
    <script id="script" type="text/paperscript" canvas="">
        var path

        var resetBtn = document.getElementById('reset')
        var input = document.getElementById('svgInput')

        var submitVisible = false
        window.app = {
          curveless: new Tool({
            onMouseDown: function(event){
              path = new Path();
              path.strokeColor = 'black';
              path.strokeWidth = '2'
              path.add(event.point);
            },
            onMouseDrag: function(event) {
              path.lineTo(event.point);
              var submit = document.getElementById('submit')
              if (!submitVisible) {
                submit.classList.remove('d-none')
                submitVisible = true
              }
            },
            onMouseUp: function(event){
              var svg = project.exportSVG({ asString: true });
              svgInput.value = svg
            }
          }),
        };

        resetBtn.addEventListener('click', function(){
          project.clear()
          submit.classList.add('d-none')
          submitVisible = false
        })

        var h = document.querySelectorAll('.part-image.head')
        var b = document.querySelectorAll('.part-image.body')
        var t = document.querySelectorAll('.part-image.legs')
        pick()
        setInterval(function(){
          pick()
        }, 3000);
        document.addEventListener('keydown', checkKey);

        function checkKey(e) {
          if (e.code = "Space") {
            pick()
          }
        }

        function pick(){
          if (h.length > 0 && b.length > 0 && t.length > 0) {
            var img = document.querySelectorAll('.part-image.selected')
            var rh = Math.ceil(Math.random() * h.length - 1 )
            var rb = Math.ceil(Math.random() * b.length - 1 )
            var rt = Math.ceil(Math.random() * t.length - 1 )
            for (var i = 0; i < img.length; i++) {
              img[i].classList.remove('selected')
            }
            h[rh].classList.add('selected')
            b[rb].classList.add('selected')
            t[rt].classList.add('selected')
          }else {
            document.body.classList.add('add')
          }

        }
    </script>
    <script>
        var parts = ['legs','body','head'];
        var r = Math.round(Math.random() * 2);
        var chosenPart = parts[r];
        document.body.classList.add(chosenPart);
        document.getElementById('script').setAttribute('canvas',chosenPart);
        document.getElementById('partInput').setAttribute('value',chosenPart);
    </script>
@endsection
