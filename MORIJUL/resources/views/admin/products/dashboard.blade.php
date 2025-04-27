<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            background-color: #433F3F;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        nav {
            top: 0;
            position: relative;
            width: 100%;
        }

        .navbar-toggler {
            border: none;
            outline: none;
        }

        .custom-toggler-icon {
            width: 30px;
            height: 3px;
            background-color: #ffffff;
            display: block;
            margin: 5px 0;
            transition: 0.3s ease-in-out;
        }

        .navbar-toggler:hover .custom-toggler-icon {
            background-color: #ff9800;
        }

        .navbar-collapse {
            transition: height 0.5s ease-in-out;
        }

        .container {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            flex-grow: 1; /* Membuat container utama fleksibel agar mengisi ruang */
        }

        .table {
            background-color: #fff8f8; /* Warna abu-abu untuk tabel */
        }

        .table th, .table td {
            background-color: #555555; /* Warna abu-abu terang untuk isi tabel */
            color: #ffffff; /* Warna teks */
        }

        .form-group label {
            font-weight: bold;
        }

        .btn-custom {
            margin-top: 10px;
        }

        .table img {
            width: 100px;
        }

        footer {
            width: 100%;
            align-self: flex-end; /* Memastikan footer berada di bagian bawah */
        }

        /* Styling for buttons alignment */
        .btn-container {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .btn-container form, .btn-container a {
            margin-bottom: 10px;
        }

    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-black">
        <div class="container-fluid">
            <a class="navbar-brand mx-auto position-absolute start-50 translate-middle-x" href="{{ route('admin.dashboard') }}">
                <img src="{{ url('asset/logo.png') }}" alt="logoweb" style="height: 150px;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('cart.index') }}">
                            <i class="fas fa-shopping-cart" style="font-size: 1.5em;"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('products') }}">
                            <i class="fas fa-home" style="font-size: 1.5em;"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                                 <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#adminChatModal">
                                <i class="fas fa-comment-dots" style="font-size: 1.5em;"></i>
                            </a>
                        </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5 text-white" style="background-color: #201F1F;">
        <h1 class="mb-5">Admin Dashboard</h1>
        <!-- Table to display orders -->
        <h2>Orders</h2>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Buyer Name</th>
                        <th>Room Number</th>
                        <th>Total Purchase</th>
                        <th>Payment Type</th>
                        <th>Payment Status</th>
                        <th>Verification Code</th>
                        <th>Checkout Date & Time</th>
                        <th>Ordered Products</th> <!-- New column for products -->
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td>{{ $order->buyer_name }}</td>
                        <td>{{ $order->room_number }}</td>
                        <td>{{ $order->total_amount }}</td>
                        <td>{{ $order->payment_type }}</td>
                        <td>{{ $order->payment_status }}</td>
                        <td>{{ $order->verification_code }}</td>
                        <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d-m-Y H:i:s') }}</td>
                        <td>
                            <ul>
                                @foreach($order->items as $item)
                                    <li>{{ $item->product->name }} (x{{ $item->quantity }}) - {{ $item->price }} IDR</li>
                                @endforeach
                            </ul>
                        </td>
                        <td>
                            <!-- Button to update payment status -->
                            <form action="{{ route('admin.updatePaymentStatus', $order->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('PUT')

                                <div class="input-group">
                                    <input type="text" name="code" class="form-control form-control-sm"
                                           placeholder="Enter code…" required>
                                    <button class="btn btn-success btn-sm" type="submit">
                                        Mark as Done
                                    </button>
                                </div>
                            </form>

                            <!-- Button to delete order -->
                            <form action="{{ route('admin.deleteOrder', $order->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Buttons section with proper alignment -->
        <div class="btn-container mt-3">
            <a href="{{ route('admin.products') }}" class="btn btn-primary">Manage Products</a>
            <form action="{{ route('logout') }}" method="POST" onsubmit="return confirm('Are you sure you want to logout?');">
                @csrf
                <button type="submit" class="btn btn-danger">Logout</button>
            </form>
        </div>
    </div>

    <footer class="text-center text-white mt-5 bg-black">
        <div class="container bg-black">
            <p>&copy; 2024 Morijul. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Admin Chat Modal -->
<div class="modal fade" id="adminChatModal" tabindex="-1" aria-labelledby="adminChatModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content bg-light">
        <div class="modal-header">
            <h5 class="modal-title" id="adminChatModalLabel">
                Chat with: <strong id="chat-with-label">—</strong>
              </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          {{-- List of user codes --}}
          <div class="row">
            <div class="col-md-4 border-end">
            <!-- inside the adminChatModal -->
            <ul class="list-group" id="code-list">
                @foreach($codes as $code)
                  @php $name = \App\Models\Order::where('verification_code',$code)->value('buyer_name'); @endphp
                  <li class="list-group-item list-group-item-action"
                      data-code="{{ $code }}"
                      data-name="{{ $name }}">
                    {{ $code }}
                  </li>
                @endforeach
            </ul>
            </div>
            <div class="col-md-8">
              {{-- Chat window --}}
              <div id="admin-chat-window" style="height:400px; overflow-y:auto; background:#f8f9fa; padding:15px;">
                <p class="text-muted">Select a code to view messages…</p>
              </div>
              <form id="admin-chat-form" class="d-none mt-3">
                @csrf
                <div class="input-group">
                  <input type="text" id="admin-message" name="message"
                         class="form-control" placeholder="Type your reply…" required>
                  <button class="btn btn-primary" id="admin-send-btn" type="submit">Send</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

      <!-- scripts: make sure this runs after bootstrap.bundle.js -->
      <script>
      document.addEventListener('DOMContentLoaded', function(){
        const adminName  = "{{ session('admin_username','Admin') }}",
              listItems  = document.querySelectorAll('#code-list .list-group-item'),
              chatWin    = document.getElementById('admin-chat-window'),
              chatForm   = document.getElementById('admin-chat-form'),
              chatLabel  = document.getElementById('chat-with-label'),
              msgInput   = document.getElementById('admin-message');
        let   currentCode = null;

        // code selection
        listItems.forEach(li => li.addEventListener('click', async function(){
          listItems.forEach(x=>x.classList.remove('active'));
          this.classList.add('active');

          currentCode = this.dataset.code;
          const buyerName = this.dataset.name || 'User';
          chatLabel.innerText = `${buyerName} (${currentCode})`;

          // fetch history
          let res = await fetch(`/admin/chats/${currentCode}/fetch`);
          let chats = await res.json();

          // render
          chatWin.innerHTML = '';
          chats.forEach(c => {
            const side = c.sender==='admin' ? 'text-end' : 'text-start';
            const time = new Date(c.created_at)
                           .toLocaleTimeString([], {hour:'2-digit',minute:'2-digit'});
            const who  = c.sender==='admin' ? adminName : buyerName;
            chatWin.innerHTML += `
              <div class="${side}">
                <small style="color:#ccc">${time}</small><br>
                <strong>${who}:</strong> ${c.message}
              </div>`;
          });

          chatWin.scrollTop = chatWin.scrollHeight;
          chatForm.classList.remove('d-none');
        }));

        // send reply
        chatForm.addEventListener('submit', async function(e){
          e.preventDefault();
          const msg = msgInput.value.trim();
          if(!msg||!currentCode) return;

          await fetch(`/admin/chats/${currentCode}/send`, {
            method:'POST',
            headers:{
              'Content-Type':'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ message: msg })
          });

          // optimistic UI
          const now = new Date().toLocaleTimeString([], {hour:'2-digit',minute:'2-digit'});
          chatWin.innerHTML += `
            <div class="text-end">
              <small style="color:#ccc">${now}</small><br>
              <strong>${adminName}:</strong> ${msg}
            </div>`;
          msgInput.value = '';
          chatWin.scrollTop = chatWin.scrollHeight;
        });
      });
      </script>
</body>
</html>
