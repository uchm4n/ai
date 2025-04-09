ALWAYS ANSWER IN GEORGIAN LANGUAGE

<links>
    @foreach($results as $result)
        <link>
                <title>{{$result['title_ka']}}</title>
                <description>{{$result['all']}}</description>
        </link>

        <substance>Substance: {{$result['substance']}}</substance>
    @endforeach
</links>
