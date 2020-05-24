
<form method="post" action="{{route('atualiza.usuario')}}">

<div class="card shadow">
    <div class="card-header border-0">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="mb-0">Dados do Usuario <code>{{$usuario->name}}</code></h3>
            </div>
            <div class="col text-right">
                <a type="button" data-dismiss="modal" aria-label="Close" class="btn btn-sm btn-danger">Cancelar</a>
                <button type="submit" class="btn btn-sm btn-success">Atualizar</button>
            </div>
        </div>
    </div>
    <div class="col-md-12">
    <div class="row">
    @csrf
    
    <div class="col-md-12">
        <div class="form-group">
            <label class="form-control-label">Nome:</label>
            <input type="text" class="form-control form-control-alternative" name="nome" value ="{{$usuario->name}}">
        </div>
      </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <label class="form-control-label">E-mail:</label>
            <input type="email" class="form-control form-control-alternative" name="email" value ="{{$usuario->email}}">
        </div>
      </div>
      <div class="col-md-12">
        <div class="form-group">
            <label class="form-control-label">Senha:</label>
            <input type="password" class="form-control form-control-alternative" name="senha">
        </div>
      </div>
      <div class="col-md-12">
        <div class="form-group">
            <label class="form-control-label">Confirmar Senha:</label>
            <input type="password" class="form-control form-control-alternative" name="conf_senha">
        </div>
      </div>

      <div class="col-md-12">
        <div class="form-group">
            <label class="form-control-label">Permissão:</label>
            <select name="permissao" class="form-control">
                @if($usuario->role_id == 0)
                    <option value="{{$usuario->role_id}}">Usuario</option>
                @endif
                @if($usuario->role_id == 1)
                    <option value="{{$usuario->role_id}}">Adminitrador</option>
                @endif
                <option value="0">Usuario</option>
                <option value="1">Administrador</option>

            </select>
           
        </div>
      </div>
</div>

<input type="hidden" value="{{$usuario->id}}" name="usuario_id">
</form>
</div>

