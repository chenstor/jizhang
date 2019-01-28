<div class="footer">Copyright &copy; <?php echo date("Y");?> <?php echo siteName;?></div>

</div>
</div>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/itlu.js"></script>
<script language="javascript" type="text/javascript" src="js/DatePicker/WdatePicker.js"></script>
</body>
</html>
<?php
$conn->close();
clearstatcache();
ob_end_flush();
?>