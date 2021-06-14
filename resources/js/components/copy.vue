<template>
    <section>
        <form @submit.prevent="productInsert" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-6">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="">Product Name</label>
                            <input type="text" v-model="form.title" placeholder="Product Name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">Product SKU</label>
                            <input type="text" v-model="form.sku" placeholder="Product Name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">Description</label>
                            <textarea v-model="form.description" id="" cols="30" rows="4" class="form-control"></textarea>
                        </div>
                    </div>
                </div>


            </div>

        </div>

        <button type="submit" class="btn btn-lg btn-primary">Save</button>
        <button type="button" class="btn btn-secondary btn-lg">Cancel</button>
        </form>
    </section>
</template>
<script>

    export default {
        data(){
        	return{
        		form:{
        			title :'',
        			sku :'',
        			description:'',
        			file_path:'',
                    variant_id:'',
                    price:'',
                    stock:'',
        		}
        	}
        },
        
        methods:{ 	

        	productInsert(){
            axios.post('/product/',this.form).then(response => {
                console.log(response.data);
            }).catch(error => {
                console.log(error);
            })
        	},
        	
        }
    	
    }



  
</script>