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
                <h3 class="card-title">Create New
                </h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <form
  @submit="checkForm"
  action="/invoice/store"
  method="post" 
  enctype="multipart/form-data"
>
<div class="row">
    <div class="col-md-12">
        @if (Session::has('error'))
                        <div class="alert alert-danger">
                            {{ Session::get('error') }}
                        </div>
                    @endif
                    @if (Session::has('success'))
                        <div class="alert alert-success">
                            {{ Session::get('success') }}
                        </div>
                    @endif
        @if($errors->any())
        <ul>
    <?= implode('', $errors->all('<li>:message</li>'))?>
</ul>
@endif
        <p v-if="errors.length">
            <b>Please correct the following error(s):</b>
            <ul>
              <li v-for="error in errors">@{{ error }}</li>
            </ul>
          </p>
        
    </div>
</div>
               <div class="row">
                @csrf
                <div class="col-md-3">
                    <label for="">Name</label><span class="test test-danger">*</span>
                   <input id="name"
                   v-model="name" type="text" class="form-control" name="name">
                </div>
                <div class="col-md-3">
                    <label for="">Date</label><span class="test test-danger">*</span>
                   <input id="date"
                   v-model="date" type="date" class="form-control " name="date">
                </div>
                <div class="col-md-3">
                    <label for="">Filename </label><span class="test test-danger">*</span>
                   <input @change="onFileChange()"  ref="file" type="file" class="form-control " name="file">
                </div>
                <div class="col-md-3">
                   <button type="button" @click="increment()" style="margin-top: 26px " class="btn btn-success"><i class="fa fa-plus"></i></button>
                </div>
               </div>

               <div class="row" v-for="(n,index) in cntarr":key="cntarr[index]">
                <div class="col-md-3">
                    <label for="">Qty</label><span class="test test-danger">*</span>
                   <input id="qty"
                   v-model="qty[index]" type="number" class="form-control" name="qty[]">
                </div>
                <div class="col-md-3">
                    <label for="">Tax</label><span class="test test-danger">*</span>
                   <input id="tax"
                   v-model="tax[index]" step="0.01" type="number" class="form-control " name="tax[]">
                </div>
                <div class="col-md-3">
                    <label for="">Amount </label><span class="test test-danger">*</span>
                    <input id="amount"
                    v-model="amount[index]" step="0.01" type="number" class="form-control " name="amount[]">
              
                </div>
                <div class="col-md-3">
                    <button type="button" @click="remove(index)" style="margin-top: 26px " class="btn btn-danger"><i class="fa fa-minus"></i></button>
                 </div>
               </div>
               
               <div class="row">
                <div class="col-md-12">
                  <br>
                    <center>  
                      <a href="/invoice" >
                        <button type="button"   class="btn btn-danger">Back</button>
                   
                      </a>
                      &nbsp;
                      <button type="submit"   class="btn btn-info">Save</button>
                    </center>
                   </div>
              </div>
                </form>
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
            errors: [],
          cnt:0,
          cntarr:[],
          qty: [],
          tax: [],
          amount: [],
          allowedtype:["image/jpeg","image/jpg","image/png","application/pdf"],
        }
      },methods: {
    increment() {
      
      this.cntarr.push(this.cnt++)
    },remove(index) {
      
      this.cntarr.splice(index, 1);
    
    },
    onFileChange(e) {
        const file = this.$refs.file.files[0];
        if (!file) { this.errors.push('No file chosen'); }else{
            if (file.size > 3024 * 3024) {  this.errors.push('File too big (> 3MB)');  }
            if (this.allowedtype.includes(file.type) == false) {  this.errors.push('Only Allowed jpg,png,pdf');  }
            
         
        }
     
            },

    checkForm: function (e) {
    const file = this.$refs.file.files[0];
    this.errors = [];
      if (!file) { this.errors.push('No file chosen'); e.preventDefault(); }
      
      
      

      if (!this.name) {
        this.errors.push('Name required.');
        e.preventDefault();
      }
      if (!this.date) {
        this.errors.push('date required.');
        e.preventDefault();
      }
      if(this.qty.length <= 0)
      {
        this.errors.push('Qty required.');
        e.preventDefault();
      }
      if(this.tax.length <= 0)
      {
        this.errors.push('Tax required.');
        e.preventDefault();
      }
      if(this.amount.length <= 0)
      {
        this.errors.push('Amount required.');
        e.preventDefault();
      }
      
      if(file)
      {
        if (file.size > 3024 * 3024) {  this.errors.push('File too big (> 3MB)');  e.preventDefault();}
        if (this.allowedtype.includes(file.type) == false) {  this.errors.push('Only Allowed jpg,png,pdf'); e.preventDefault(); }
           
      }
      
     
      if ( this.errors.length == 0) {  
     
        return true; }
      
     
    }
  }
    }).mount('#app')
  </script>
    
@endsection