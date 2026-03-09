<h3 class="mt-3 text-primary">Fornecedor</h3>

<div class="card shadow mt-3">
    <form method="post" name="formsalvar" id="formSalvar" class="m-3" enctype="multipart/form-data">
        <div class="form-group row">
            <label for="txtnome" class="col-sm-2 col-form-label">Nome</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="txtnome" name="txtnome" placeholder="Nome do fornecedor" value="">
            </div>
        </div>
        <div class="form-group row">
            <label for="txttelefone" class="col-sm-2 col-form-label">Telefone</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="txttelefone" name="txttelefone" placeholder="(00) 0000-0000" value="">
            </div>
        </div>
        <div class="form-group row">
            <label for="txtemail" class="col-sm-2 col-form-label">E-mail</label>
            <div class="col-sm-10">
                <input type="email" class="form-control" id="txtemail" name="txtemail" placeholder="email@provedor.com" value="">
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-10">
                <input type="submit" class="btn btn-primary" name="btnsalvar" value="Cadastrar">
            </div>
            <a href="?p=fornecedores" class="btn btn-danger">Cancelar</a>
        </div>
    </form>
</div>