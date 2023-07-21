<x-app-layout>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Participantes</h1>
                    </div>
                    {{-- <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item"><a href="#">Layout</a></li>
                            <li class="breadcrumb-item active">Fixed Layout</li>
                        </ol>
                    </div> --}}
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">

            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <!-- Default box -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Editar participante</h3>

                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                            title="Collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <div class="card-body table-responsive p-0">
                                <form method="POST" action="{{ route('participante.update', $participante->id) }}"
                                      enctype="multipart/form-data">
                                    @csrf
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="name">Nome*</label>
                                            <input type="text" class="form-control" id="name" name="name"
                                                   placeholder="Nome" value="{{ $participante->name }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Email*</label>
                                            <input type="text" class="form-control" id="email" name="email"
                                                   placeholder="Email" value="{{ $participante->email }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="password">Senha</label>
                                            <input type="password" class="form-control" id="password" name="password"
                                                   placeholder="Senha">
                                            <small id="passwordHelp" class="form-text text-muted">Deixar a senha em
                                                branco caso não queira alterá-la.</small>
                                        </div>
                                        <div class="form-group">
                                            <label for="phone">Telefone*</label>
                                            <input type="text" class="form-control" id="phone" name="phone"
                                                   placeholder="Telefone" value="{{ $participante->phone }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="cpf">CPF*</label>
                                            <input type="text" class="form-control" id="cpf" name="cpf"
                                                   placeholder="CPF" value="{{ $participante->cpf }}">
                                        </div>
                                    </div>
                                    <!-- /.card-body -->
                                    <div class="form-check pb-3">
                                        <div class="custom-switch">
                                            <input type="checkbox"
                                                   @if ($participante->status == 1) checked="checked" @endif
                                                   class="custom-control-input" name="status" id="status"
                                                   value="1">
                                            <label class="custom-control-label" for="status">Ativo</label>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-primary">Salvar</button>
                                    </div>
                                </form>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
</x-app-layout>
