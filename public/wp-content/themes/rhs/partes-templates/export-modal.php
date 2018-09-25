<div id="exportModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h3 class="modal-title">Exportar Resultados da Busca para <span id="format_file"></span></h3>
      </div>
      <div class="modal-body text-center">
        <div id='result_type_csv'>
          <?php show_results_from_search('csv'); ?>
        </div>
        <div id='result_type_xls'>
          <?php show_results_from_search('xls'); ?>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
      </div>
    </div>

  </div>
</div>