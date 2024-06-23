<div>
    <h5 class="text-center fs-1 py-3">Users
    </h5>

    <div class="row m-4 gap-5  d-flex justify-content-around">

        @foreach ($users as $user)

        <div class="col-sm-12 col-md-4 col-lg-3 col-xl-3 bg-white border card border-secondary-subtle shadow rounded">
            <div class=" d-flex flex-column card-body align-items-center pb-5 pt-3">
                <img src="https://cdn.pixabay.com/photo/2017/08/06/21/01/louvre-2596278_960_720.jpg" class=" mb-3 rounded-circle shadow-lg card-img" alt="image" style="width: 100px; height:100px">

                <h5 class="mb-1 text-xl-center font-medium text-gray-900 card-title">{{$user->name}}</h5>

                <span class="text-sm text-gray-500 card-text">{{$user->email}}</span>

                <div class="d-flex mt-4 space-x-6 mt-md-6">

                    <x-secondary-button class="me-2">
                        Add Friend
                    </x-secondary-button>

                    <x-button wire:click="message({{$user->id}})">
                        Message
                    </x-button>
                </div>
            </div>
        </div>
        @endforeach

    </div>
</div>

{{-- <div class="text-center">
    <button wire:click="increment">+</button>
                            <h1>{{$count}}</h1>
                            <button wire:click="decrement">-</button>
    <br>
        <button wire:click="message" class="btn btn-primary">say Hi</button>
</div> --}}
