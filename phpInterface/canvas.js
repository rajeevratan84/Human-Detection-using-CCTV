
    (function () {


        var div = document.getElementById('image');
        div.addEventListener('mousedown', mousedown);
        div.addEventListener('mouseup', mouseup);
        div.addEventListener('mousemove', mousemove);

        var grab = false;
        var rect = {
            x0: 0,
            y0: 0,
            x1: 0,
            y1: 0
        };

        function mousedown(e) {
            grab = true;
            rect.x0 = e.clientX;
            rect.y0 = e.clientY;

        }

        function mousemove(e) {
            if (grab) {
                rect.x1 = e.clientX;
                rect.y1 = e.clientY;
                showRect();
            }
        }

        function mouseup(e) {
            grab = false;


        }

        function showRect() {
            var rectDiv = document.getElementById('rect');
            rectDiv.style.display = 'block';
            rectDiv.style.position = 'absolute';
            rectDiv.style.left = rect.x0 + 'px';
            rectDiv.style.top = rect.y0 + 'px';
            rectDiv.style.width = (rect.x1 - rect.x0) + 'px';
            rectDiv.style.height = (rect.y1 - rect.y0) + 'px';

            var boundsDiv = document.getElementById('bounds');
            boundsDiv.innerText = 'crop rect: ' + rect.x0 + ',' + rect.y0 + ' to ' + rect.x1 + ',' + rect.y0;
        }

    })();

