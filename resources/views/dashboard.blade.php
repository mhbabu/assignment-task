@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        {{ __('You are logged in!') }}
                    </div>
                </div>
            </div>
        </div>
        @if (auth()->user()->user_type === 'manager')
            <div class="row">
                <div class="col-md-6">
                    <div class="card mt-5">
                        <div class="card-header">
                            <h5>Verified Users</h5>
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                  <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Mobile</th>
                                    <th scope="col">Date OF Birth</th>
                                  </tr>
                                </thead>
                                <tbody>
                                    @forelse($verifiedUsers as $key => $verifiedUser)
                                    <tr>
                                        <th scope="row">{{ ++$key}}</th>
                                        <td>{{ $verifiedUser->name }}</td>
                                        <td>{{ $verifiedUser->email }}</td>
                                        <td>{{ $verifiedUser->mobile }}</td>
                                        <td>{{ $verifiedUser->date_of_birth }}</td>
                                      </tr>
                                    @empty
                                    <tr class="text-center">
                                        <td colspan="5">No records found...!</td>
                                      </tr>
                                    @endforelse
                                </tbody>
                              </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card mt-5">
                        <div class="card-header">
                            <h5>Verified Users</h5>
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                  <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Mobile</th>
                                    <th scope="col">Date OF Birth</th>
                                  </tr>
                                </thead>
                                <tbody>
                                    @forelse($unVerifiedUsers as $key => $unVerifiedUser)
                                    <tr>
                                        <th scope="row">{{ ++$key}}</th>
                                        <td>{{ $unVerifiedUser->name }}</td>
                                        <td>{{ $unVerifiedUser->email }}</td>
                                        <td>{{ $unVerifiedUser->mobile }}</td>
                                        <td>{{ $unVerifiedUser->date_of_birth }}</td>
                                      </tr>
                                    @empty
                                    <tr class="text-center">
                                        <td colspan="5">No records found...!</td>
                                      </tr>
                                    @endforelse
                                </tbody>
                              </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
