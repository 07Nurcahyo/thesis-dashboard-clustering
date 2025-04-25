<html>
  <head>
    <title>JQVMap - World Map</title>
    {{-- <link href="../dist/jqvmap.css" media="screen" rel="stylesheet" type="text/css"> --}}
    <link rel="stylesheet" href="{{asset('adminlte/plugins/jqvmap/jqvmap.css')}}" media="screen" type="text/css">

    <script type="text/javascript" src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
    {{-- <script type="text/javascript" src="../dist/jquery.vmap.js"></script> --}}
    {{-- <script type="text/javascript" src="../dist/maps/jquery.vmap.world.js" charset="utf-8"></script> --}}
    <script src="{{asset('adminlte/plugins/jqvmap/jquery.vmap.min.js')}}"></script>
    <script src="{{asset('adminlte/plugins/jqvmap/maps/jquery.vmap.indonesia.js')}}"></script>

    <script type="text/javascript">
    jQuery(document).ready(function() {
      // jQuery('#vmap').vectorMap({ map: 'world_en' });
      jQuery('#vmap').vectorMap(
      {
          map: 'indonesia_id',
          backgroundColor: '#a5bfdd',
          borderColor: 'black',
          borderOpacity: 0.25,
          borderWidth: 1,
          color: 'green',
          enableZoom: true,
          hoverColor: '#c9dfaf',
          hoverOpacity: null,
          normalizeFunction: 'linear',
          scaleColors: ['#b6d6ff', '#005ace'],
          selectedColor: '#c9dfaf',
          selectedRegions: null,
          showTooltip: true,
          onRegionClick: function(element, code, region)
          {
              var message = 'You clicked "'
                  + region
                  + '" which has the code: '
                  + code.toUpperCase();

              alert(message);
          }
      });
    });
    </script>
  </head>
  <body>
    <div id="vmap" style="width: 600px; height: 400px;"></div>
  </body>
</html>