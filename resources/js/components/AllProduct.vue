<template>
    <div class="d-flex w-100">
        <div class="col-md-2">
            <h2>Filter</h2>
                <div class="searchFilter d-flex flex-column">
                    <label for="" class="d-flex flex-column"><span><strong>Product: </strong></span><input type="text" v-model="search"></label>
                    <label for="" class="d-flex flex-column"><span><strong>Availability: </strong></span><input type="number" v-model="available"></label>
                    <label for="" class="d-flex flex-column"><span><strong>Category: </strong></span><input type="text" v-model="category"></label>
                    <label for="" class="d-flex flex-column"><span><strong>Price: </strong></span>Min<input type="text" v-model="min_price"> Max <input type="text" v-model="max_price"></label>
                    <button class="btn btn-primary" v-on:click='filter'>Filter</button>
                </div>
        </div>
        <div class="col-md-10">
            <h2 class="text-left mb-4">Products</h2>
                <div class="row product-list">
                    <div class="col-md-3" v-if="seen">
                        <p>No Result</p>
                    </div>
                    <div class="col-md-3" v-for="product in products.data" :key="product.id" >
                        <b-card
                            img-top
                            tag="article"
                            style="max-width: 20rem;"
                            class="mb-2">
                            <img src="https://picsum.photos/600/300/?image=25" alt="">
                            <h3>Name: {{ product.name }}</h3>
                            <b-card-text>
                                <div class="price"><h5>Price: {{ product.price }}</h5></div>
                                <p><strong>Description:</strong> {{ product.description }} </p>
                                <div class="price"><h5>Category: {{ product.category.name }}</h5></div>
                                <div class="price"><h5>In Store Availability: {{ product.available }}</h5></div>
                            </b-card-text>
                        </b-card>
                    </div>
                    
                </div>
                <!-- <pagination align="center" :data="products" @pagination-change-page="product"></pagination> -->

                <pagination :data="products" @pagination-change-page="product" class="mt-5 text-center">
                    <span slot="prev-nav">&lt; Previous</span>
                    <span slot="next-nav">Next &gt;</span>
                </pagination>
            
        </div> 
    </div>
</template>
<style>
img{
    width: 100%;
}
h3 {
    font-size: 21px!important;
    margin-top: 20px;
}
</style>
<script>
    import pagination from 'laravel-vue-pagination'

    export default {
        name:"products",
        components:{
            pagination
        },
        data() {
            return {
                seen: false,
                search: null,
                available: null,
                min_price: null,
                max_price: null,
                category: null,
                products:{
                    type:Object,
                    default:null
                }
            }
        },

        mounted(){
            this.product()
        },


        methods:{
            async product(page=1){
                await axios.get(`http://127.0.0.1:8000/api/products?page=${page}`).then(({data})=>{
                     this.products = data;
                }).catch(({ response })=>{
                    console.error(response)
                })
            },
            filter: function(){
                axios.get('http://127.0.0.1:8000/api/search', { params: { 
                    search: this.search, 
                    available: this.available, 
                    min_price: this.min_price,
                    max_price: this.max_price,
                    category: this.category 
                } })
                .then((response) => {
                    if(response.data.length  != 0){
                        this.products = response;
                        this.seen = false
                    }else{
                        this.products = [];
                        this.seen = true
                    }
                })
                .catch(error => {
                    console.log(error)
                });

            }
        }
    }
</script>
