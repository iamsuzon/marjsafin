@forelse($linkList ?? [] as $item)
    <tr>
        <td>{{ $item->id }}</td>
        <td>{{ $item->created_at->format('d/m/Y') }}</td>
        <td>{{ucfirst($item->type)}}</td>
        <td>
            <p>FN: {{ $item->first_name }}</p>
            <p>LS: {{ $item->last_name }}</p>
            <p>NID: {{$item->nid_number }}</p>
            <p class="text-capitalize">Gender: {{ $item->gender }}</p>
        </td>
        <td>{{ $item->passport_number }}</td>
        <td>{{ $item->links()->count() }}</td>
        <td>
            @foreach($item->links ?? [] as $link)
                <a href="{{ $link->url }}" target="_blank">{{ $loop->iteration }}. {{ Str::limit($link->url, 40) }}</a>
            @endforeach
        </td>
        <td>
            @if($item->links()->count() < 1)
                <a href="javascript:void(0)" class="btn btn-primary link-now-btn" data-id="{{ $item->id }}">Link Now</a>
            @endif
        </td>
    </tr>
@empty
    <tr>
        <td colspan="10" class="text-center">No Data Found</td>
    </tr>
@endforelse
