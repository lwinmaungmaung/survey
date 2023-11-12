@extends('template.public')
@section('title',$form->name)
@section('body')
    <h1 class="h1">{{$form->name}}</h1>
    <p class="my-3">{{$form->description}}</p>
    <form action="{{route('public.form.submit',$form)}}" method="post">
        @csrf
        @forelse($form->fields as $index=>$field)
            <div class="row my-3">
                <label for="inputPassword5" class="form-label">{{$field->name}}{{$field->required?"*":''}}</label>
                @switch($field->type)
                    @case('text')
                        <input name="{{$field->name}}" value="{{old($field->name)}}" type="{{$field->type}}" id="inputPassword5" class="form-control"
                               aria-describedby="passwordHelpBlock" {{ $field->required?'required':'' }}>
                        @break
                    @case('textarea')
                        <textarea name="{{$field->name}}" type="{{$field->type}}" id="inputPassword5" class="form-control"
                                  aria-describedby="passwordHelpBlock" {{ $field->required?'required':'' }}>{{old($field->name)}}</textarea>
                        @break
                    @case('select')
                        <select name="{{$field->name}}" type="{{$field->type}}" id="inputPassword5" class="form-control"
                                aria-describedby="passwordHelpBlock" {{ $field->required?'required':'' }}>
                            @foreach(json_decode($field->options) as $option)
                                <option>{{$option}}</option>
                            @endforeach
                        </select>
                        @break
                    @case('radio')
                        <div class="row">
                            @forelse(json_decode($field->options) as $option)
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input name="{{$field->name}}" value="{{$option}}" class="form-check-input" type="radio" name="flexRadioDefault"
                                               id="flexRadioDefault1" {{ $field->required?'required':'' }}>
                                        <label class="form-check-label" for="flexRadioDefault1">
                                            {{$option}}
                                        </label>
                                    </div>
                                </div>
                            @empty
                            @endforelse
                        </div>
                        @break
                    @case('checkbox')
                        @forelse(json_decode($field->options) as $option)
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input name="{{$field->name}}[]" value="{{$option}}" class="form-check-input" type="checkbox" name="flexRadioDefault"
                                           id="flexRadioDefault1" {{ $field->required?'required':'' }}>
                                    <label class="form-check-label" for="flexRadioDefault1">
                                        {{$option}}
                                    </label>
                                </div>
                            </div>
                        @empty
                        @endforelse
                @endswitch
            </div>

        @empty
            <p>Nothing to Display</p>
        @endforelse
        <input type="submit" value="{{__('form.submit')}}">
    </form>


@endsection
