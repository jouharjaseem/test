@extends("layouts.applayout")
@section("content")

   
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" id="app">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>DataTables</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">DataTables</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <!-- /.card -->

            <div class="card">
              <div class="card-header">
                <h3 class="card-title"> <a href="/invoice/create"  ><button class="btn btn-info">Create</button></a>
                </h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Name</th>
                    <th>Date</th>
                    <th>File</th>
                    <th>Edit</th>
                    <th>Delete</th>
                  </tr>
                  </thead>
                  <tbody>
                  
                  <tr v-for="(n,index) in cnt":key="cnt[index]">
                    <td>@{{n.name}}</td>
                    <td>@{{n.date}}
                    </td>
                    <td><a  :href="'/upload/user/' + n.filename"> Download</a></td>
                    <td><a :href="'/invoice/edit/' + n.id"   ><button class="btn btn-warning">Edit</button></a>
                    </td>
                    <td><button @click="remove(index,n.id)" class="btn btn-danger">Delete</button>
                    </td>
                  </tr>
                
                  </tbody>
                 
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  
  <script>
    
    const { createApp } = Vue
    
    createApp({
      data() {
        return {
         
          cnt:<?=$result?>,
          
        }
      },methods: {
        remove(index,id) {
      
  fetch('http://127.0.0.1:8000/destroy/'+id);
      this.cnt.splice(index, 1);
    
    }
      }
    }).mount('#app')
  </script>
    
@endsection