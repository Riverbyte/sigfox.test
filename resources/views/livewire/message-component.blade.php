<div class="pt-4 container mx-auto">

    <button wire:click='render' id="actualiza">Click</button>
    
    <div class="bg-white rounded-lg shadow overflow-hidden max-w-4xl mx-auto ">
        <table >
            <thead class="bg-gray-50 border-b border-gray-200 ">
                <tr class="text-xs font-medium text-gray-500 uppercase tracking-wider ">
                    <th class="px-6 py-3">ID</th>
                    <th class="px-6 py-3">name</th>
                    <th class="px-6 py-3">DEVICE</th>
                    <th class="px-6 py-3">DATA</th>
                    <TH class="px-6 py-3">TIME</TH>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200 ">
                @foreach ($messages as $message)
                    <tr class="text-gray-500 ">
                        <td class="py-2 px-6">{{$message->ID}}</td>
                        <td class="py-2 px-6">{{$message->NAME}}</td>
                        <td class="py-2 px-6">{{$message->DEVICE}}</td>
                        <td class="py-2 px-6">{{$message->DATA}}</td>
                        <td class="py-2 px-6">{{$message->TIME}}</td>
                        
                        
                    </tr>
                    
                @endforeach

            </tbody>
        </table>

        <div class="bg-gray-100 px-6 py-4 border-t border-gray-200 ">
            {{$messages->links()}}
        </div>
    </div>

</div>


<script>

var myVar = setInterval(myTimer, 10000);

function myTimer() {
  document.getElementById("actualiza").click();
} 

</script>


