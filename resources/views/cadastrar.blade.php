@extends('admin-template.dashboard')

@push('link')
<style>
    .fc-day-past {
        background-color: rgba(255, 159, 137, 0.16);
    }

    .fc-day-future {
        background-color: rgb(143, 223, 130, 0.16);
    }

    .fc-daygrid-event {
        background-color: rgba(174, 214, 241, 0.9);
        border: 1px solid rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')
<div id='calendar'></div>

<div class="modal fade" id="cadastrar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Cadastrar Evento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-event" method="post" action="{{ url('register') }}">
                    @csrf
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Nome</label>
                        <div class="col-sm-10">
                            <input type="text" name="name" class="form-control" id="Nome" placeholder="Nome" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Data</label>
                        <div class="col-sm-10">
                            <input type="date" name="start" class="form-control" id="start" readonly="readonly">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Horários Disponíveis</label>
                        <div class="col-sm-10">
                            <select name="time" class="form-control" id="time" required>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-12 text-right">
                            <input id="datetime" type="hidden" name="datetime">
                            <input type="submit" value="Cadastrar" class="btn btn-success">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    /* Função que exibe mensagens de erros e sucesso quanto em contato com uma função PHP */
    <?php if (count($errors) > 0) : ?>

        var temp = '';
        $('#msg').modal('show');
        $('#span-msg').addClass('text-danger');

        <?php foreach ($errors->all() as $error) : ?>

            temp += '<p class="mb-0">{{$error}}</p>';

        <?php endforeach; ?>

        $('#span-msg').html(temp);

    <?php elseif (session('success')) : ?>

        $('#msg').modal('show');
        $('#span-msg').addClass('text-success');
        $('#span-msg').html('{{ session("success") }}');

    <?php endif; ?>
</script>
<script src="{{ asset('/js/cadastrar.min.js') }}"></script>
@endpush