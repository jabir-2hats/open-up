 @if ($tags->isEmpty())
     <span class="text-gray-500">No tags</span>
 @else
     @foreach ($tags as $tag)
         <span class="badge badge-soft badge-info">{{ $tag }}</span>
     @endforeach
 @endif
