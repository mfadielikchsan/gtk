<footer class="main-footer">
	<div class="pull-right hidden-xs">
	  <b>Version</b> 1.0.0
	</div>
	<strong>Copyright &copy; {{ date("Y")>2017 ? "2017-".date("Y") : date("Y") }} {{ strtoupper(config('app.nm_pt', 'Laravel')) }}.</strong> All rights reserved. Email: <a href="mailto:portal.ite@igp-astra.co.id">IT PT. {{ config('app.kd_pt', 'XXX') }}</a>. Phone: 021-4602755 (Ext. 184)
	@if(!empty(request()->route()->getAction()['id_programmer']))
    	({{ "@".request()->route()->getAction()['id_programmer'] }})
    @else 
	    @if(!empty(request()->route()->getAction()['id_sysdev']))
	    	({{ "@".request()->route()->getAction()['id_sysdev'] }})
	    @endif
  	@endif
</footer>