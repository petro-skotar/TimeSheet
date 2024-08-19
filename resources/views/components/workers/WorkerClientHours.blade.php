@if(!empty($WorkerClientHours))
    @foreach($WorkerClientHours->unique('worker_id') as $wc)
        @if($wc->worker()->active)
        <div class="col-xl-6 d-flex">
            <form id="u{{ $wc->worker_id }}" data-id="{{ $wc->worker_id }}" data-salary="{{ $wc->worker()->salary }}" data-position="{{ $wc->worker()->position }}" data-clientsid="@if(!empty($wc->get_connect_clients_id())){{ implode(',',$wc->get_connect_clients_id()) }}@endif" class="card {{ (($selectCountDays == 7 && $WorkerClientHours->where('worker_id',$wc->worker_id)->sum('hours') < 36 || in_array($selectCountDays, [28,29,30,31]) && $WorkerClientHours->where('worker_id',$wc->worker_id)->sum('hours') < 150) ? 'few-days' : (($selectCountDays == 7 && $WorkerClientHours->where('worker_id',$wc->worker_id)->sum('hours') > 44 || in_array($selectCountDays, [28,29,30,31]) && $WorkerClientHours->where('worker_id',$wc->worker_id)->sum('hours') > 180) ? 'many-days' : 'bg-white')) }} d-flex flex-fill">
                <div class="card-body pt-3" style="flex: none;">
                    <div class="row">
                        <div class="col-9">
                            @if(!empty($wc->worker()->position))
                            <div class="text-muted pb-1" title="{{ $wc->worker()->position }}" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                {{ $wc->worker()->position }}
                            </div>
                            @endif
                            <h2 class="lead"><a href="#" class="b600 editWorkerFromClientClick data_name" data-toggle="modal" data-target="#popup__editWorkerFromClient">{{ $wc->worker()->name }}</a></h2>
                            @if(!empty($wc->worker()->phone) || !empty($wc->worker()->email))
                            <ul class="ml-4 mb-0 fa-ul text-muted">
                                <li class="small"><span class="fa-li"><i class="far fa-envelope"></i></span> <a href="mailto:{{ $wc->worker()->email }}" class="data_email">{{ $wc->worker()->email }}</a></li>
                                @if(!empty($wc->worker()->phone) && 2==3)<li class="small"><span class="fa-li"><i class="fas fa-phone"></i></span> <a href="tel:{{ $wc->worker()->phone }}">{{ $wc->worker()->phone }}</a></li>@endif
                            </ul>
                            @endif
                        </div>
                        <div class="col-3 text-right">
                            <img alt="{{ $wc->worker()->name }}" class="worker-avatar img-circle img-fluid" src="{{ (!empty($wc->worker()->image) && File::exists('storage/'.$wc->worker()->image) ? asset('storage/'.$wc->worker()->image) : asset('vendor/adminlte/dist/img/no-usericon.svg')) }}">
                        </div>
                    </div>
                </div>
                <div class="card-body p-0" style="flex: 100%;">
                    @if($selectCountDays == 1)
                        @include('components/workers/user-data-day')
                    @else
                        @include('components/workers/user-data-period')
                    @endif
                </div>
                <div class="card-footer" style="flex: none;">
                    <div class="row">
                        <div class="col-sm-6">
                            @if($selectCountDays == 1)
                            <button type="button" class="btn btn-default btn-xs addClientHoursButton" data-toggle="modal" data-target="#addClientHours" data-worker_id="{{ $wc->worker_id }}">
                                <i class="far fa-clock"></i> Добавить часы работы
                            </button>
                            @endif
                        </div>
                        <div class="col-sm-6">
                            <div class="text-right">
                                <span class="data-total"><i class="far fa-clock"></i> <span id="wc_{{ $wc->worker()->id }}">{{ $WorkerClientHours->where('worker_id',$wc->worker_id)->sum('hours') }}</span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        @endif
    @endforeach
@endif