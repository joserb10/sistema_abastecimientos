<template>
<main class="main">
    <!-- Breadcrumb -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
    </ol>
    <div class="container-fluid">
        <div class="card">
            <div class="card-header ">
                
            </div>
            <div class="car-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card card-chart">
                            <div class="card-header">
                                <h4>Ventas perfectas - Pre</h4>
                            </div>
                            <div class="card-content bg-dark">
                                <div class="ct-chart">
                                    <canvas id="ventas_perfectas_pre">                                                
                                    </canvas>
                                </div>
                            </div>
                            <div class="card-footer d-flex justify-content-between">
                                <p>Ventas perfectas del mes de noviembre 2020. </p>
                                <h3><span class="badge badge-info text-white">{{ind_pre_perfectas}}</span><span class="badge badge-success text-white">{{p_pre_perfectas}}%</span></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card card-chart">
                            <div class="card-header">
                                <h4>Ventas perfectas - Post</h4>
                            </div>
                            <div class="card-content bg-dark">
                                <div class="ct-chart">
                                    <canvas id="ventas_perfectas_post">                                                
                                    </canvas>
                                </div>
                            </div>
                            <div class="card-footer d-flex justify-content-between">
                                <p>Ventas perfectas de diciembre 2020 a febrero 2021.</p>
                                <h3><span class="badge badge-info text-white">{{ind_post_perfectas}}</span><span class="badge badge-success text-white">{{p_post_perfectas}}%</span></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header ">  
            </div>
            <div class="car-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card card-chart">
                            <div class="card-header">
                                <h4>Ventas a tiempo - Pre</h4>
                            </div>
                            <div class="card-content bg-dark">
                                <div class="ct-chart">
                                    <canvas id="ventas_atiempo_pre">                                                
                                    </canvas>
                                </div>
                            </div>
                            <div class="card-footer d-flex justify-content-between">
                                <p>Ventas a tiempo del mes de noviembre 2020. </p>
                                <h3><span class="badge badge-info text-white">{{ind_pre_atiempo}}</span><span class="badge badge-success text-white">{{p_pre_atiempo}}%</span></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card card-chart">
                            <div class="card-header">
                                <h4>Ventas a tiempo - Post</h4>
                            </div>
                            <div class="card-content bg-dark">
                                <div class="ct-chart">
                                    <canvas id="ventas_atiempo_post">                                                
                                    </canvas>
                                </div>
                            </div>
                            <div class="card-footer d-flex justify-content-between">
                                <p>Ventas a tiempo de diciembre 2020 a febrero 2021.</p>
                                <h3><span class="badge badge-info text-white">{{ind_post_atiempo}}</span><span class="badge badge-success text-white">{{p_post_atiempo}}%</span></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            <div class="card">
            <div class="card-header">
                
            </div>
            <div class="car-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card card-chart">
                            <div class="card-header">
                                <h4>Rotación de inventarios - Pre</h4>
                            </div>
                            <div class="card-content">
                                <div class="ct-chart">
                                    <canvas id="ventas_rotacion_pre">                                                
                                    </canvas>
                                </div>
                            </div>
                            <div class="card-footer d-flex justify-content-between">
                                <h6>Rotación de inventarios noviembre 2020: </h6>
                                <h3><span class="badge badge-success text-white">{{ind_rotacion_pre}}</span></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card card-chart">
                            <div class="card-header">
                                <h4>Rotación de inventarios - Post</h4>
                            </div>
                            <div class="card-content">
                                <div class="ct-chart">
                                    <canvas id="ventas_rotacion_post">                                                
                                    </canvas>
                                </div>
                            </div>
                            <div class="card-footer d-flex justify-content-between" style="padding-top: 5px !important; margin-bottom: -13px;">
                                <h6>Rotación de inventarios diciembre 2020: </h6>
                                <h3><span class="badge badge-success text-white">{{ind_rotacion_post_dic}}</span></h3>
                            </div>
                            <div class="card-footer d-flex justify-content-between" style="padding-top: 5px !important; margin-bottom: -13px;">
                                <h6>Rotación de inventarios enero 2021: </h6>
                                <h3><span class="badge badge-success text-white">{{ind_rotacion_post_ene}}</span></h3>
                            </div>
                            <div class="card-footer d-flex justify-content-between" style="padding-top: 5px !important; margin-bottom: -13px;">
                                <h6>Rotación de inventarios febrero 2021: </h6>
                                <h3><span class="badge badge-success text-white">{{ind_rotacion_post_feb}}</span></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="alert alert-dark" role="alert">
               <h5 class="text-center"> "La rotación de inventarios será más adecuada entre más se aleje de 1". (Suárez, 2017)</h5>
            </div>
        </div>
        <!-- <div class="card">
            <div class="card-header">
                
            </div>
            <div class="car-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card card-chart">
                            <div class="card-header">
                                <h4>Ingresos</h4>
                            </div>
                            <div class="card-content">
                                <div class="ct-chart">
                                    <canvas id="ingresos">                                                
                                    </canvas>
                                </div>
                            </div>
                            <div class="card-footer">
                                <p>Compras de los últimos meses.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card card-chart">
                            <div class="card-header">
                                <h4>Ventas</h4>
                            </div>
                            <div class="card-content">
                                <div class="ct-chart">
                                    <canvas id="ventas">                                                
                                    </canvas>
                                </div>
                            </div>
                            <div class="card-footer">
                                <p>Ventas de los últimos meses.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
    </div>

</main>
</template>
<script>
    import Chart from 'chart.js';
    export default {
        
        data (){
            return {
                varIngreso:null,
                charIngreso:null,
                ingresos:[],
                varTotalIngreso:[],
                varMesIngreso:[], 
                
                varVenta:null,
                charVenta:null,
                ventas:[],
                varTotalVenta:[],
                varMesVenta:[],

                perfectas_pre: [],
                varPerfectasPre: null,
                charPerfectasPre: null,
                ind_pre_perfectas: '',
                p_pre_perfectas: 0,

                perfectas_post: [],
                varPerfectasPost: null,
                charPerfectasPost: null,
                ind_post_perfectas: '',
                p_post_perfectas: 0,

                atiempo_pre: [],
                varAtiempoPre: null,
                charAtiempoPre: null,
                ind_pre_atiempo: '',
                p_pre_atiempo: 0,

                atiempo_post: [],
                varAtiempoPost: null,
                charAtiempoPost: null,
                ind_post_atiempo: '',
                p_post_atiempo: 0,

                ind_rotacion_pre: 0,
                cant_vendidas_pre: [],
                inv_prom_pre: [],
                varRotacionPre: null,
                charRotacionPre: null,

                ind_rotacion_post_dic: 0,
                cant_vendidas_post_dic: [],
                inv_prom_post_dic: [],

                ind_rotacion_post_ene: 0,
                cant_vendidas_post_ene: [],
                inv_prom_post_ene: [],
                
                ind_rotacion_post_feb: 0,
                cant_vendidas_post_feb: [],
                inv_prom_post_feb: [],
                varRotacionPost: null,
                charRotacionPost: null,
            }
        },
        methods : {
            getIngresos(){
                let me=this;
                var url= '/dashboard';
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.ingresos = respuesta.ingresos;
                    //cargamos los datos del chart
                    me.loadIngresos();
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getVentas(){
                let me=this;
                var url= '/dashboard';
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.ventas = respuesta.ventas;
                    //cargamos los datos del chart
                    me.loadVentas();
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getData(){
                let me=this;
                var url= '/dashboard/reportes';
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.perfectas_pre = respuesta.array_perfectas_pre;
                    me.perfectas_post = respuesta.array_perfectas_post;
                    me.atiempo_pre = respuesta.array_atiempo_pre;
                    me.atiempo_post = respuesta.array_atiempo_post;
                    me.ind_rotacion_pre = respuesta.rotacion_pre;
                    me.ind_rotacion_post_dic = respuesta.rotacion_post_dic;
                    me.ind_rotacion_post_ene = respuesta.rotacion_post_ene;
                    me.ind_rotacion_post_feb = respuesta.rotacion_post_feb;
                    me.cant_vendidas_pre = respuesta.cantidad_vendidas_pre;
                    me.inv_prom_pre = respuesta.inv_prom_pre;
                    me.cant_vendidas_post_dic = respuesta.cantidad_vendidas_post_dic;
                    me.inv_prom_post_dic = respuesta.inv_prom_post_dic;
                    me.cant_vendidas_post_ene = respuesta.cantidad_vendidas_post_ene;
                    me.inv_prom_post_ene = respuesta.inv_prom_post_ene;
                    me.cant_vendidas_post_feb = respuesta.cantidad_vendidas_post_feb;
                    me.inv_prom_post_feb = respuesta.inv_prom_post_feb;
                    //cargamos los datos del chart
                    me.loadPerfectasPre();
                    me.loadPerfectasPost();
                    me.loadAtiempoPre();
                    me.loadAtiempoPost();
                    me.loadRotacionPre();
                    me.loadRotacionPost();
                    // me.loadRotacionPost();
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            loadIngresos(){
                let me=this;
                me.ingresos.map(function(x){
                    me.varMesIngreso.push(x.mes);
                    me.varTotalIngreso.push(x.total);
                });
                me.varIngreso=document.getElementById('ingresos').getContext('2d');

                me.charIngreso = new Chart(me.varIngreso, {
                    type: 'bar',
                    data: {
                        labels: me.varMesIngreso,
                        datasets: [{
                            label: 'Ingresos',
                            data: me.varTotalIngreso,
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 0.2)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero:true
                                }
                            }]
                        }
                    }
                });
            },
            loadVentas(){
                let me=this;
                me.ventas.map(function(x){
                    me.varMesVenta.push(x.mes);
                    me.varTotalVenta.push(x.total);
                });
                me.varVenta=document.getElementById('ventas').getContext('2d');

                me.charVenta = new Chart(me.varVenta, {
                    type: 'bar',
                    data: {
                        labels: me.varMesVenta,
                        datasets: [{
                            label: 'Ventas',
                            data: me.varTotalVenta,
                            backgroundColor: 'rgba(255, 195, 0, 0.2)',
                            borderColor: 'rgba(255, 195, 0, 0.2)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero:true
                                }
                            }]
                        }
                    }
                });
            },
            loadPerfectasPre(){
                let me=this;

                me.ind_pre_perfectas = me.perfectas_pre[1]+'/'+me.perfectas_pre[0];
                me.p_pre_perfectas = (me.perfectas_pre[1]*100)/ me.perfectas_pre[0];
                me.p_pre_perfectas = me.p_pre_perfectas.toFixed(2);
                
                me.varPerfectasPre=document.getElementById('ventas_perfectas_pre').getContext('2d');

                me.charPerfectasPre = new Chart(me.varPerfectasPre, {
                    type: 'pie',
                    data: {
                        labels: ['Ventas Perfectas', 'Ventas Observadas'],
                        datasets: [{
                            // label: 'Ventas Perfectas',
                            data: me.perfectas_pre,
                            backgroundColor: ['rgba(255, 87, 51, 1)',
                                            'rgba(218, 247, 166, 1)',
                                            ],
                            borderColor: ['rgba(255, 255, 255)',
                                        'rgba(255, 255, 255)' ,                               
                            ],

                            borderWidth: 2
                        }]
                    },
                    options: {
                        // scales: {
                        //     yAxes: [{
                        //         ticks: {
                        //             beginAtZero:false
                        //         }
                        //     }]
                        // }
                    }
                });
            },
            loadPerfectasPost(){
                let me=this;

                me.ind_post_perfectas = me.perfectas_post[1]+'/'+me.perfectas_post[0];
                me.p_post_perfectas = (me.perfectas_post[1]*100)/ me.perfectas_post[0];
                me.p_post_perfectas = me.p_post_perfectas.toFixed(2);
                
                me.varPerfectasPost=document.getElementById('ventas_perfectas_post').getContext('2d');

                me.charPerfectasPost= new Chart(me.varPerfectasPost, {
                    type: 'pie',
                    data: {
                        labels: ['Ventas Perfectas', 'Ventas Observadas'],
                        datasets: [{
                            // label: 'Ventas Perfectas',
                            data: me.perfectas_post,
                            backgroundColor: ['rgba(255, 87, 51, 1)',
                                            'rgba(218, 247, 166, 1)',
                                            ],
                            borderColor: ['rgba(255, 255, 255)',
                                        'rgba(255, 255, 255)' ,                               
                            ],

                            borderWidth: 2
                        }]
                    },
                    options: {
                        // scales: {
                        //     yAxes: [{
                        //         ticks: {
                        //             beginAtZero:false
                        //         }
                        //     }]
                        // }
                    }
                });
            },
            loadAtiempoPre(){
                let me=this;

                me.ind_pre_atiempo = me.atiempo_pre[1]+'/'+me.atiempo_pre[0];
                me.p_pre_atiempo = (me.atiempo_pre[1]*100)/ me.atiempo_pre[0];
                me.p_pre_atiempo = me.p_pre_atiempo.toFixed(2);
                
                me.varAtiempoPre=document.getElementById('ventas_atiempo_pre').getContext('2d');

                me.charAtiempoPre = new Chart(me.varAtiempoPre, {
                    type: 'pie',
                    data: {
                        labels: ['Ventas A tiempo', 'Ventas A destiempo'],
                        datasets: [{
                            // label: 'Ventas Perfectas',
                            data: me.atiempo_pre,
                            backgroundColor: ['rgba(46, 227, 115, 1)',
                                            'rgba(46, 191, 227, 1)',
                                            ],
                            borderColor: ['rgba(255, 255, 255)',
                                        'rgba(255, 255, 255)' ,                               
                            ],

                            borderWidth: 2
                        }]
                    },
                    options: {
                        // scales: {
                        //     yAxes: [{
                        //         ticks: {
                        //             beginAtZero:false
                        //         }
                        //     }]
                        // }
                    }
                });
            },
            loadAtiempoPost(){
                let me=this;

                me.ind_post_atiempo = me.atiempo_post[1]+'/'+me.atiempo_post[0];
                me.p_post_atiempo = (me.atiempo_post[1]*100)/ me.atiempo_post[0];
                me.p_post_atiempo = me.p_post_atiempo.toFixed(2);
                
                me.varAtiempoPost=document.getElementById('ventas_atiempo_post').getContext('2d');

                me.charAtiempoPost= new Chart(me.varAtiempoPost, {
                    type: 'pie',
                    data: {
                        labels: ['Ventas A tiempo', 'Ventas A destiempo'],
                        datasets: [{
                            // label: 'Ventas Perfectas',
                            data: me.atiempo_post,
                            backgroundColor: ['rgba(46, 227, 115, 1)',
                                            'rgba(46, 191, 227, 1)',
                                            ],
                            borderColor: ['rgba(255, 255, 255)',
                                        'rgba(255, 255, 255)' ,                               
                            ],

                            borderWidth: 2
                        }]
                    },
                    options: {
                        // scales: {
                        //     yAxes: [{
                        //         ticks: {
                        //             beginAtZero:false
                        //         }
                        //     }]
                        // }
                    }
                });
            },
            loadRotacionPre(){
                let me=this;

                me.ind_rotacion_pre = me.ind_rotacion_pre.toFixed(2); 
                me.varRotacionPre=document.getElementById('ventas_rotacion_pre').getContext('2d');

                me.charRotacionPre = new Chart(me.varRotacionPre, {
                    type: 'bar',
                    data: {
                        labels: ['Noviembre'],
                        datasets: [{
                            label: 'Cantidades Vendidas',
                            data: [me.cant_vendidas_pre],
                            backgroundColor: 'rgba(217, 57, 18, 0.7)',
                            borderColor: 'rgb(217, 57, 18)',
                            borderWidth: 1
                        },
                        {
                            label: 'Inventario Promedio',
                            data: [me.inv_prom_pre],
                            backgroundColor: 'rgba(99,194,222, 0.7)',
                            borderColor: 'rgb(99,194,222)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero:true
                                }
                            }]
                        }
                    }
                });
            },
            loadRotacionPost(){
                let me=this;

                me.ind_rotacion_post_dic = me.ind_rotacion_post_dic.toFixed(2); 
                me.ind_rotacion_post_ene = me.ind_rotacion_post_ene.toFixed(2); 
                me.ind_rotacion_post_feb = me.ind_rotacion_post_feb.toFixed(2); 
                me.varRotacionPost=document.getElementById('ventas_rotacion_post').getContext('2d');

                me.charRotacionPost = new Chart(me.varRotacionPost, {
                    type: 'bar',
                    data: {
                        labels: ['Diciembre', 'Enero', 'Febrero'],
                        datasets: [{
                            label: 'Cantidades Vendidas',
                            data: [me.cant_vendidas_post_dic, me.cant_vendidas_post_ene, me.cant_vendidas_post_feb],
                            backgroundColor: 'rgba(217, 57, 18, 0.7)',
                            borderColor: 'rgb(217, 57, 18)',
                            borderWidth: 1
                        },
                        {
                            label: 'Inventario Promedio',
                            data: [me.inv_prom_post_dic, me.inv_prom_post_ene, me.inv_prom_post_feb],
                            backgroundColor: 'rgba(99,194,222, 0.7)',
                            borderColor: 'rgb(99,194,222)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero:true
                                }
                            }]
                        }
                    }
                });
            },
        },
        mounted() {
            this.getIngresos();
            this.getVentas();
            this.getData();
        }
    }
</script>
