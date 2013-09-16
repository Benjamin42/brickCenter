<?php if(!class_exists('raintpl')){exit;}?><!--é-->
<!--***************-->
<!-- [TOUS] FOOTER -->
<!--***************-->

<footer> 
TODO
</footer>


<!--*******************-->
<!-- [TOUS] JAVASCRIPT -->
<!--*******************-->

<script type="text/javascript" src="./tpl/./js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="./tpl/./js/jquery-ui-1.10.1.custom.min.js"></script>
<script type="text/javascript" src="./tpl/./js/jquery-migrate-1.0.0.js"></script>

<script type="text/javascript" src="./tpl/./js/jquery.fileupload.js"></script>
<script type="text/javascript" src="./tpl/./js/jquery.iframe-transport.js"></script>

<script type="text/javascript" src="./tpl/./js/jquery.poshytip.min.js"></script>

<script type="text/javascript" src="./tpl/./js/main.js"></script>
<script type="text/javascript" src="./tpl/./js/tinypop.min.js"></script>

<script type="text/javascript" src="./tpl/./js/bootstrap.min.js"></script>


<?php if( isset($_GET['error']) ){ ?>

<script type="text/javascript">  TINYPOP.show("<?php echo $_GET['error'];?>", {position: 'top-right',timeout: 3000,sticky: false});</script>
<?php } ?>


</body>
</html>
