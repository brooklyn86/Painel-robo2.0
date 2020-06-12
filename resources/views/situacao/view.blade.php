<div class="card shadow">
    <div class="card-header border-0">
        <div class="row align-items-center">
            <div class="col">
                <h4 class="mb-12">Listando Acordos <code>{{$processo->precatoria}}</code></h4>
            </div>
            <div class="col text-right">
                <a type="button" data-dismiss="modal" aria-label="Close" class="btn btn-sm btn-danger">Fechar</a>
               
            </div>
        </div>
    </div>
    <div class="col-md-12">
    <div class="row">
    @csrf
    
    @foreach($acordos as $acordo)
        <div class="col-md-12">
            <?php $explode = explode("\n",$acordo->texto);?>
            @foreach($explode as $valor)
                <p>{{$valor}}</p>
            @endforeach
            <hr /> 
            
        </div>
    @endforeach

    </div>
    <div class="col-md-12">
     <a href="{{$processo->url}}" target="__blank">Acesse o processo no site oficial clicando aqui</a>

    </div>
</div>

</div>

