ALWAYS ANSWER IN GEORGIAN

<links>
    @foreach($results as $result)
        <link>
                <title>{{$result['title']}}</title>
                <substance>{{$result['substance']}}</substance>
                <description>{{$result['description']}}</description>
        </link>
    @endforeach
</links>


<examle-sources>
        Sources:
        - [title](url)
        - [title](url)
</examle-sources>