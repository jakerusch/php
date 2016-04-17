<!-- <?php
require_once($_SERVER['DOCUMENT_ROOT']."/shopping_list/include/classyJake.php");
$obj = new classyJake();
$pageName = basename(__FILE__, '.php');
$obj->createPage($pageName);
$conn = $obj->getConn();
?>

<a href="#" id="1" qty="5" class="push" data-toggle="modal" data-target="#myModal">Apples</a> 
<a href="#" id="2" qty="1" class="push" data-toggle="modal" data-target="#myModal">Oranges</a> 

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"></h4>
      </div>
      <div class="modal-body">
        <div id="modalContent">Update quantity to...</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary updateQty" data-dismiss="modal">1</button>
        <button type="button" class="btn btn-primary updateQty" data-dismiss="modal">2</button>
        <button type="button" class="btn btn-primary updateQty" data-dismiss="modal">3</button>
        <button type="button" class="btn btn-primary updateQty" data-dismiss="modal">4</button>
        <button type="button" class="btn btn-primary updateQty" data-dismiss="modal">5</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>
    <script>
      $(function(){
        $('.updateQty').click(function() {
          var qty = $(this).text();
          var item_id = $(this).
          $.ajax({
            type: "POST",
            url: "modaltest.php",
            data: {item_id: item_id, qty: qty},
            cache: false,
            success: function(response) {
              alert(response);
            }
          })
        });
        $('.push').click(function(){
          var item_id = $(this).text();
          var qty = $(this).attr("qty");
              $('#myModal').show();
              $('#myModalLabel').html(text+ " x "+qty);
              // $('#modalContent').html(qty);
              // $('#modalContent').show().html(response);          
          // var id = $(this).attr('id');
          // $.ajax({
          //   type: 'POST',
          //   url: 'modaltest.php',
          //   data: {id: id, text: text},
          //   cache: false,
          //   success: function(response) {
          //     // alert(response);
          //     $('#myModal').show();
          //     $('#myModalLabel').html(text);
          //     $('#modalContent').show().html(response);
          //   }
          // });
        });
      });
    </script>
  </body>
</html> -->