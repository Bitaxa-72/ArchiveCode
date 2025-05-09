<$set $isIE = ''|isIE$>

<$set $cfg = $.const.SBOX_CFG$>
<$set $cfg_home = $cfg.main$>

<$set $ID = ''|resource:'id'$>
<$set $tmpl = ''|resource:'template'$>
<$set $ctx = $modx->context->key$>
<$set $lexicon = $cfg.lexicon[$ctx]$>

<$set $bodyClasses = [
	0 => ''|resource:'template'==2 ? 'homepage' : 'innerpage',
	1 => $cfg.main.bgr_options ?: 'bgr-auto',
	2 => $cfg.main.site_mode == 2 ? 'landing' : '',
	3 => $cfg.main['btns-radius'] == 999 ? 'btns-max-radius' : '',
	4 => $isIE==1 ? 'ie' : '',
	5 => $cfg.blocks[20].classes|match:'*over-hero*' ? 'has-header-over-hero' : '',
	6 => $cfg.blocks[20].classes|match:'*over-nav*' ? 'has-header-over-nav' : '',
	7 => 'id-' ~ $ID
]$>


<$$_modx->lexicon->load('cultureKey'|config~':core:default')$>


<!doctype html>
<html lang='<$'cultureKey'|config$>'>
	
	<head>
		<$insert '000-HEAD'$>
		<$if $.get.noscripts != 1$>
		    <$4|ctx|resource:'content'|process$>
		<$/if$>
		<$block 'style'$><$/block$>
	</head>
	
	<body class="<$$bodyClasses|join:' '$>">
		<div id="page">
	
			<$*верхнее меню и шапка*$>
			<$include 'block' id=20$>
			
			<script>
				window.onscroll = function() {
					var cl = document.body.classList;
					window.scrollY > 10
						? !cl.contains('is-scrolled') && cl.add('is-scrolled')
						: cl.contains('is-scrolled') && cl.remove('is-scrolled')
				}
			
				/*определяем высоту шапки*/
				function getHeaderHeight() {
					var header = document.querySelector('.header'),
						nav = document.querySelector('.topnav'),
						hdrH = header ? header.scrollHeight : 0,
						navH = nav ? nav.scrollHeight : 0
					document.documentElement.style.setProperty('--hw-height', hdrH + navH  + 'px');
				}
				
				var headerLogoImg = document.querySelector('.header .logo img')
				if (headerLogoImg) headerLogoImg.onload = getHeaderHeight;
				window.addEventListener('resize', function(event) {
				    getHeaderHeight();
				});
				
				getHeaderHeight();
			</script>
					
		
			<$*заголовок + крошки*$>
			<$if $tmpl != 2$>
			    <$include 'block' id=62$>
			<$/if$>
			
			<$block 'page'$>
			
				<div class="content container">
				    <$if ''|resource:'id'==258$>
					    <$include 'block' id=407$>
					<$/if$>
					<div class="tinymce">
						<$if ''|resource:'id'==120$>
							<$'!SimpleSearch'|snippet$>
						<$else$>
							<$''|resource:'content'|process:['cfg'=>$cfg]$>
						<$/if$>
					</div>
					<$*фотогалерея*$>
					<$if ''|resource:'tv.gallery'$>
					    <$include 'block' id=216$>
					<$/if$>
					
				</div><!-- content -->
				<$*карта*$>
				<$if ''|resource:'id'==16$>
				    <$include 'block' id=70$>
				<$/if$>
				    <$if ''|resource:'id'==16$>
					    <$include 'block' id=406$>
					<$/if$>
				
	
				<$*контейнеры*$>
				<$include 'container' bid=(''|resource:'tv.container') this=$cfg['blocks'][(''|resource:'tv.container')]$>
				
			<$/block$>
			
			<$*подвал*$>
			<$include 'block' id=31$>
			<$if $cfg_home.site_modules|match:'*recaptcha*'$>
			    <$include 'footer-recaptcha'$>
			<$/if$>
		</div><!-- #page -->
			
		<$include '999-AFTER'$>
		
		<$*скрипты*$>
		<$if $.get.noscripts != 1$>
            <$123|ctx|resource:'content'|process$>
		<$/if$>
		
	</body>
</html>