	</div><!-- /#wrapper -->    
    <!-- jQuery -->
    <script src="js/jquery.js"></script>
	<script src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js"></script>
	<script>
	    $(document).ready(function() {
	        $('table.display').DataTable({
	        	"lengthMenu": [5, 10, 20, 50, 100],
        		"pageLength": 5
	        });
	    } );
	</script>
    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

</body>

</html>