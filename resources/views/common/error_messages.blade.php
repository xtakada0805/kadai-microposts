    @if (count($errors) > 0)
        <ul class="aleart aleart-danger" role="aleart">
            @foreach ($errors->all() as $error)
                <li class="ml-4">{{ $error }}</li>
            @endforeach
        </ul>
    @endif