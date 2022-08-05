<x-app-layout>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Organizadores</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Layout</a></li>
                <li class="breadcrumb-item active">Fixed Layout</li>
                </ol>
            </div>
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
                    <h3 class="card-title">Editar organizador - {{$owner->name}}</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
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
                    <form method="POST" action="{{route('owner.update', $owner->id)}}" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="name">Nome</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Nome" value="{{$owner->name}}">
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="text" class="form-control" id="email" name="email" placeholder="Email" value="{{$owner->email}}">
                            </div>
                            <div class="form-group">
                                <label for="password">Senha</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Senha">
                            </div>
                            <div class="form-group">
                                <label for="icon">Banner da empresa de organização</label>
                                @if(!$owner->icon)
                                    <input class="form-control" type="file" id="icon" name="icon">
                                @else
                                <div class="form-group">
                                    <img src="{{ asset('storage/'.$owner->icon) }}" alt="Banner da empresa de organização" class="img-fluid img-thumbnail" style="width: 200px">
                                    <a href="{{route('owner.delete_file', $owner->id)}}" class="btn btn-danger">Excluir</a>
                                </div>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="description">Descrição</label>
                                <textarea type="password" class="form-control" id="description" name="description" rows="10">{{$owner->description}}</textarea>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="form-check pb-3">
                          <div class="custom-switch">
                              <input type="checkbox" @if($owner->status == 1) checked="checked" @endif class="custom-control-input" name="status" id="status" value="1">
                              <label class="custom-control-label" for="status">Ativo</label>
                          </div>
                        </div>
                        <div class="card-footer">
                          <button type="submit" class="btn btn-primary">Salvar</button>
                        </div>
                    </form>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    Footer
                </div>
                <!-- /.card-footer-->
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
