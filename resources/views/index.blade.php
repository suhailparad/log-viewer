<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
    <title>{{ $title }}</title>
    <style>
        .fs-12px{
            font-size: 12px;
        }
        .fs-13px{
            font-size: 13px;
        }.fs-14px{
            font-size: 14px;
        }
        .fw-500{
            font-weight: 500;
        }
        .py-10px{
            padding-top:10px !important;
            padding-bottom:10px !important;
        }
        .container-custom{
            margin-left: 64px !important;
            margin-right: 64px !important;
        }
        .type-error{
            background: rgb(220, 53, 69);
            text-transform: capitalize;
        }
        .type-info{
            background: rgb(61, 131, 235);
            text-transform: capitalize;
        }
        .type-debug{
            background: rgb(61, 131, 235);
            text-transform: capitalize;
        }
        .type-warning{
            background: rgb(245, 146, 32);
            text-transform: capitalize;
        }
        .active-file{
            border-color: rgb(61, 131, 235) !important;
            background: rgb(229, 239, 255) !important;
        }
    </style>
</head>

<body style="background-color: #f6f6f6">
    <div class="container-custom my-4">
        <h1 class="fs-3" >{{ $title }}</h1>
        <div>
            <a href="{{$app_url}}" class="text-secondary text-decoration-none fs-14px" >
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="18px"
                    fill="#5f6368">
                    <path d="M0 0h24v24H0z" fill="none" />
                    <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z" />
                </svg>
                Back to {{$app_name}}
            </a>
        </div>
    </div>

    <div class="container-custom">
        <div class="row">
            <div class="col-3">
                @foreach ($log_files as $file )
                    <a href="/logs/{{ $file['id'] }}" class="px-3 py-10px mb-2 border rounded bg-white d-flex align-items-baseline text-decoration-none {{($log_index+1)==$file['id']?'active-file':''}}" role="button">
                        <span href="?file={{$file['id']}}" class="fs-14px fw-500 text-dark" >{{$file['name']}}</span>
                        <span class="text-secondary fs-13px fw-500  ms-auto">{{$file['size']}}</span>
                    </a>
                @endforeach
            </div>
            <div class="col-9">
                <div class="mb-2 d-flex align-items-center">
                    <div>
                        <span class="fs-14px">Total <span class="fw-500">{{ $logs->total() }}</span> Records</span>
                    </div>

                    <div class="ms-auto w-25">
                        <form id="search-form" method="GET">
                            {{-- <input type="text" class="border rounded px-3 py-2 w-100" name="query" value="{{ $query }}" placeholder="Search..." /> --}}
                            <div class="input-group">
                                <input type="text" id="search-input" class="form-control" placeholder="Search..." value="{{ $query }}" name="query"  >
                                <span class="input-group-text" id="basic-addon2">
                                    <button type="button" id="search-close" class="btn-close" aria-label="Close"  ></button>
                                </span>
                            </div>
                        </form>
                    </div>
                </div>
                @foreach ($logs as $index=>$log )
                    <div class="mb-2 border rounded bg-white">
                        <div class="row p-3 log-header" role="button">
                            <div class="col-1">
                                <div class="rounded text-center py-1 text-light fs-12px type-{{$log['type']}}">{{ $log['type'] }}</div>
                            </div>
                            <div class="col-2">
                                <span class="fs-13px text-secondary">{{$log['datetime']}} </span>
                            </div>
                            <div class="col-9">
                                <span class="fs-13px fw-500" style="color:#444" >{!! $log['title'] !!}</span>
                            </div>
                        </div>

                        @if($log['has_multi_line'])
                            <div class="border-top p-3 fs-13px log-details d-none .custom-transition">
                                {!! $log['message'] !!}
                            </div>
                        @endif

                    </div>
                @endforeach

                {{ $logs->onEachSide(5)->links('pagination::bootstrap-5') }}

            </div>
        </div>
    </div>


    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"> </script> --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $(".log-header").click(function() {
                var $logDetails = $(this).next(".log-details");

                if ($logDetails.hasClass("d-none")) {
                    // Hide all sub-divs
                    $(".log-details").addClass("d-none");
                    // Show the sibling sub-div
                    $logDetails.removeClass("d-none");
                } else {
                    $logDetails.addClass("d-none");
                }
            });

            $("#search-close").on('click',function(){
                $('#search-input').val(null);
                $('#search-form').submit();
            })

        });
      </script>

</body>
</html>
