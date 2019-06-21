<template>
<div class="messagingApp">
   <div class="appOverlay"></div>
   <div class="chatList">
      <header class="chatListHeader">
         <br>
         <h4 class="font-weight-bold"><i class="mdi mdi-message-settings"></i>&nbsp;Chats con estudiantes</h4>
      </header>
      <div class="chatOptions">
         <input type="text" @keyup="getRecordsOfUsers(buscar)" v-model="buscar" class="search" placeholder="Buscar..." />
      </div>
      <div class="chats">
         <pulse-loader class="text-center" :loading="loading" :color="color" :size="size"></pulse-loader>
         <div v-for="user in arrayUsers" :key="user.id" :id="'div-user-'+user.usuario_id" @click="user.msj_unread > 0 ? getMessages(user.usuario_id,'markRead') : getMessages(user.usuario_id,'')" class="chatUser">
            <div class="chatUserIcon">
               <img :src="'http://portal.itcha.edu.sv/images/alumnos/'+user.foto" :alt="user.usuario">
            </div>
            <div class="chatUserDetails">
               <span class="chatUsername font-weight-bold" v-text="user.usuario"></span>	
               <span class="badge badge-pill badge-danger" v-if="user.msj_unread > 0" v-text="user.msj_unread"></span>
               <small class="chatPrevMessage">{{user.message.mensaje | truncate(30)}}</small>
            </div>
         </div>
         <div v-if="arrayUsers.length == 0" class="alert alert-primary text-center" role="alert">
            No hay ninguna conversación iniciada
         </div>
      </div>
   </div>
   <div class="chatDetails">
      <button class="hamburger">
         <svg viewBox="0 0 20 20" class="hamburgerIcon">
            <line x1="2" y1="5" x2="18" y2="5" />
            <line x1="2" y1="10" x2="18" y2="10" />
            <line x1="2" y1="15" x2="18" y2="15" />
         </svg>
      </button>
      <div class="messageWrapper" id="div-msj">
         <div class="row div-information" v-if="Object.keys(dataByUser).length > 0">
            <div class="col-md-2">
               <div class="btn-group pull-xs-left">
                  <button class="btn bmd-btn-icon dropdown-toggle" type="button" id="mw2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Más opciones">
                  <i class="mdi mdi-dots-vertical"></i>
                  </button>
                  <div class="dropdown-menu dropdown-menu-left"  aria-labelledby="mw2">
                     <button class="dropdown-item d-block menu" style="cursor: pointer;" @click="deleteConversation" type="button"><i class="mdi mdi-delete-empty"></i>&nbsp;Vaciar conversación</button>
                  </div>
               </div>
            </div>
            <div class="col-md-10" v-text="dataByUser.nombre+' '+dataByUser.apellido+' - '+dataByUser.carrera+' ('+dataByUser.nivel+')'" style="padding: 10px;">
            </div>
         </div>
         <div class="messages" >
            <div  v-for="message in arrayMessages" :key="message.id">
               <div class="chat" v-text="message.mensaje" :class="[message.usuario_id == user.id ? 'me' : '']">
               </div>
               <small :class="[message.usuario_id == user.id ? 'dateMe' : 'dateOther']" v-text="message.created_at"></small>
            </div>
            <pulse-loader class="text-center" :loading="loadingMsj" :color="color" :size="size"></pulse-loader>
         </div>
      </div>
      <div class="textBoxWrapper">
         <div class="textBoxContainer">
            <br><textarea v-model="bodyMessage" @keyup.enter="sendMessage" placeholder="Escriba su mensaje..." class="form-control" rows="7"></textarea>
         </div>
         <div class="buttonGroup">
            <button :disabled="user_id == '' || bodyMessage == ''" @click="sendMessage" :class="[user_id == '' || bodyMessage == '' ? 'disabled' : '']" class="button blue btn-chat"><i class="mdi mdi-send"></i>&nbsp;Enviar</button>
         </div>
      </div>
   </div>
</div>
</template>
<script>
export default {
	props:['user'],
	data() {
	  return {
	    buscar: '',
	    loading: false,
            color: "#533fd0",
	    size: "12px",
	    arrayMessages: [],
	    loadingMsj: false,
	    bodyMessage: '',
	    user_id: '',
	    arrayUsers: [],
	    dataByUser: {}
	  }
	},
	methods:{
	  getRecordsOfUsers(){
		const toast = swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 4000});
		let me = this;
		me.loading = true;
		var url = route('getMessagesUsers',{"buscar" : me.buscar});
		axios.get(url).then(function(response) {
			var respuesta = response.data;
			me.arrayUsers = respuesta
			me.loading = false;
		})
		.catch(function(error) {
			me.loading = false;
			console.log(error);
		});  
	  },
	 getRecordsOfUsersAfterRead(){
		let me = this;
		var url = route('getMessagesUsers',{"buscar" : me.buscar});
		axios.get(url).then(function(response) {
			var respuesta = response.data;
			me.arrayUsers = respuesta
		})
		.catch(function(error) {
			console.log(error);
		});  
	  },
	  sendMessage(){
		let me = this;
		var url = route('messages.store',{'mensaje': me.bodyMessage,'receiver_id': me.user_id});
		axios.post(url).then(function(response) {
			me.bodyMessage = '';
			var respuesta = response.data;
			me.arrayMessages.push(respuesta.message);
			setTimeout(me.scrollToEnd,0.10);
			me.getRecordsOfUsersAfterRead();
		})
		.catch(function(error) {
			console.log(error);
		});
	  },
	  setReadMessages(usuario_id){
		let me = this;
		var url = route('setReadMessageAdmin',usuario_id);
		axios.post(url).then(function(response) {
			me.getRecordsOfUsersAfterRead();
			me.$emit('updmessagesunread');
		})
		.catch(function(error) {
			console.log(error);
		});
	  },
	  getMessages(id,markRead) {
		let me = this;
		me.user_id != '' ? $("#div-user-"+me.user_id).removeClass('activeUser') : '';
		me.loadingMsj = true;
		me.user_id = id;
		var url = route('getRecordsMessagesByUser',id);
		axios.get(url).then(function(response) {
			$("#div-user-"+id).addClass('activeUser');
			var respuesta = response.data;
			me.arrayMessages = respuesta.mensajes;
			me.dataByUser = respuesta.usuario;
			me.loadingMsj = false;
			setTimeout(me.scrollToEnd,0.10);
			markRead == 'markRead' ? me.setReadMessages(id) : ''; 
		}).catch(function(error) {
		  console.log(error);
		});
	  },
	  getMessagesWithoutReaload(){
	        let me = this;
		var url = route('getRecordsMessagesByUser',me.user_id);
		axios.get(url).then(function(response) {
			var respuesta = response.data;
			me.arrayMessages = respuesta.mensajes;
			me.$emit('updmessagesunread');
			me.setReadMessages(me.user_id); 
			setTimeout(me.scrollToEnd,0.10);
		}).catch(function(error) {
		  console.log(error);
		});
	  },
	  scrollToEnd(){
	      var objDiv = document.getElementById("div-msj");
	      objDiv.scrollTop = objDiv.scrollHeight;
	  },
	  deleteConversation(user_id,user_name){
	   swal({
		title: "Esta seguro de eliminar la conversación con " +this.dataByUser.nombre,
		type: "question",
		showCancelButton: true,
		confirmButtonColor: "#3085d6",
		cancelButtonColor: "#d33",
		confirmButtonText: "Aceptar",
		cancelButtonText: "Cancelar",
		confirmButtonClass: "button blue",
		cancelButtonClass: "button red",
		buttonsStyling: false,
		reverseButtons: true
	   }).then(result => {
		if (result.value) {
			let me = this;
			var url = route('deleteConversation',{'usuario_id':me.user_id});
			axios.post(url).then(function(response) {
				swal({
					position: "center",
					type: "success",
					title: "Conversación eliminada",
					showConfirmButton: true,
					width: '300px',
				}).then(function(result){
				me.user_id = '';
				me.getRecordsOfUsers();
				me.arrayMessages = [];
				me.bodyMessage = [];
				me.dataByUser = {};
				});
			})
			.catch(function(error) {
			   console.log(error);
			});
		} else if (
		// Esto lo hace cuando se descativa el registro
		result.dismiss === swal.DismissReason.cancel
		) {
		}
	    });
	  }
	},
	created() {
		let me = this;
		Echo.private('chat').listen('MessageSentEvent', (e) => {
		  if($("#btnFAB").hasClass('hiddeBtnFab')){
			me.getRecordsOfUsersAfterRead();
		        e.user.id == me.user_id ? me.getMessagesWithoutReaload() : '';
		  }
		});
	},
	mounted(){
		$("#btnFAB").addClass('hiddeBtnFab');
		this.getRecordsOfUsers();
	},
	destroyed() {
		$("#btnFAB").removeClass('hiddeBtnFab');
	}
};
</script>
<style type="scss">
	.div-information{
	position: absolute;
	height: 7%;
	z-index: 9;
	width: 100%;
	margin-left: 0px;
	font-size: 15px;
	background: #ffffff;
	font-weight: bold;
	}
	.activeUser{
	   background: #5C6BC0;
	   color: white;
	}
	.dateMe{
	  position: relative;
          float: right;
          right: 8%;
          font-size: 13px;
          margin-top: -8px;
          color: rgb(225, 225, 225);
          top: -20px;
	}
	.dateOther{
	   position: relative;
	   float: left;
	   left: 55%;
	   top: -20px;
	   font-size: 13px;
	   margin-top: -8px;
	   color: rgb(154, 154, 154);
	}
	body {
		font-family: Roboto, sans-serif;
	}
	.chatPrevMessage{
		position: absolute;
		top: 60px;
		left: 32px;
	}
	.messagingApp {
		position: relative;
		height: 100vh;
		width: 100%;
		box-shadow: 0 0.2rem 1.2rem rgba(0, 0, 0, 0.2);
		font-size: 1.4rem;
		display: flex;
		overflow: hidden;
		background: white;
		left: 7px;
	}
	.appOverlay {
		position: absolute;
		left: 0;
		right: 0;
		z-index: 1;
		width: 100%;
		height: 100%;
		background: #000;
		opacity: 0;
		transition: opacity 0.2s ease-in-out;
		pointer-events: none;
	}
	.messagingApp--navOpen .appOverlay {
		opacity: 0.3;
		pointer-events: all;
	}
	.messagingApp--navOpen .chatList {
		transform: translateX(0%);
	}
	.chatList {
		width: 70%;
		height: inherit;
		background: #f9f9f9;
		position: absolute;
		z-index: 2;
		transform: translateX(-100%);
		transition: transform 0.2s ease-in-out;
	}
	.chatDetails {
		width: 100%;
		height: inherit;
	}
	.messageWrapper {
		width: inherit;
		height: calc(100% - 9rem);
		overflow: auto;
		background-image: url('data:image/jpeg;base64,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kYR2N5fxA63/AOGuCzqz7GArkNuLgsrXOhHQlHDRns/sSjhussdFGvyS7eRI3A4vA0MxpFGpZiXkpZSNbZL+zys9h2E/IJzt9klOms8NwJz44SSGkUhRXLG0TH2ZVkS2xO9sSbLx9mO6cfATuniRGCNF2Qh9TArKVDgX2nSF7G3TJbYkrDQhSJpquElVlCh2myahzk7cNTsSjcjpHSmNlO/x2NO5oweh4KLbMKhal/ZaaET9QJRgbSyMnhUJp44ldkBt/gbejGTAbjSZXDgpSsj/AH4rskqnJn8DTsRG78I9/sUa4eWu+STqCX7E5fW0O2oeVw8qxw1KghMat+CGk2S6QJJMd/ZPkRsUU0nRCefkqF5H8n//2gAMAwEAAgADAAAAEEkpEkhlkkskttkFMklthFEFIpAlssEssIkhslpklkklEsktlFFIAsNlkgEgglAkkklsskktkkkkoglslBFtssspJskpNMsksptsgkklhktlskttkFMgkoJslsllglEsFlskskkpNkstpgtBlAElttIttlksslklEskkFEsskttlsottkFEtBgIlkslltsslhlsklEkFtpNsslsptEklMBFktpkkoAlstkkkkktklpghFkktskktgpktlpJsllttsllsghsokhEksgFlsElkFlsllsllkssttksksEskshAttkIslspttoklhklptlktkhklsosoEskBoAlltMtktAktsskktkkkltlgFtEtllEkslJlNAMkssllkstssglkkpFkstkolksststktssktplllEssstksksFtttIkltssIlskMtkktlksklttstllkoEspNNkhpsshEplglkkkssktpsoJNlskNElIlBtEtpssssllttktskklslklsNMgNkoskttsktlhskthkkksotssppstkJFlltsBkstAlNtltskllkklMllklFskkFFltlsoEkklklksktkthkgIklkshotgkJEtsIslpklIkkgltkkttlllsNlBEksstlsMsslsktlltkltkskkohstkAEklltEltkIhEssllkkIEtttkktklkklENlshBkstthkstsslsklktJElklghtgsIEkkoEtlkskllstklltsEtkslttBlsssMMsFhMttktkkskkksslopsEkBMlklkEsNsNgAkstltsElltskkkkskttEFhskJktlspEhthNNFsttkoEltshosJFBFsspEtlktskltkkkkktksklollAFklkFNhFgIttksssFkkNstsgIlttpkstllAkttNoIkklEtlttskgksltklskkthttJksNElgkFotpEslsktkEkFgpkItJMltpEktEsJlltttlslpkklhkslAMtklNktIsAsklssNgllttttsssksMkttokgkllJpBklsMskItokglkllskktksglsIktlNlMltlFposlNFshEkNsstskslllsNpsEkklltlMkslJEslplskIslklpFlJkIlkllkEoklEkktltlktMllllsktFEslNFkkktFsNtshktlskkkthshstIlssNtBNMlNtIskgtskpsllklskttkskMNIkkEssltsssltMslhlsklsklsBEsNkIlFsptkpklBsstklkssBlksstlskEltsFMllhFstkkEktttskttggoFENkllslJMostoAsloslshkssslslkklslJssFsstlgstNoshEklkkstskkllohtlsJkssltMsElpsEssskttsFllsksstosltMMhkhJllkthMtttlltthkgANlskossgsAtEgAslkEksNFlkllktlskttghoAkFltFEtBgBpspEltokEtssssgsskJFskktNktsAlFgsklssFElsMksklosklohtksJslsstgklpFANksllpFtFtggtpFJNksItlgklsplhkslkkkkstlhgoIslskooogsAkllAslgkksstNskttkpEkttsllltJkJstlNlktNltEtssslttlohptsJlstksIlslFhMssskskBsssskpMptttlEssktAllttgkkkkkkssotgAtklsoglgsBlkkoslklsElslgsksllkksllgktlBsIkllstkotlhkktlkskkkgpstlBNktkkIsMtNoJkslMlkIllkkkllskktsFFtFlJkttkklklhkktkoklJsslsgokplAMkssMlkEtotthslkttlllskktltIslkEFtksltNslhllktstskoFhtlJlsltkIkFsMsIkssNtksNskglklsstktpghlkkklNENssktMktlhtsgMslkMNgskAksslFtsEsglthslllltEtlostlAAssshkslkIsktsMktktttktpBgNkBlllFlEokthtJtlklskAlokklkllslskpsIksBslkEkIkMlkINtkslkllslJpkMlJslkokEokkNlksFsskltEllpklsIktlkJEpEsAklttsMlskMkkthphAstlspElhFstlspsktslstNktkksksEtskoEJklMklNNkgkAtkptssosottlsggkskIEstktttkklklllkktksMslkklghEtssNNllkIkklkkNsssIlNtltsksBskhglgMIsMhoklpMttAkkkgktlklslsMEFskMtsFMhAkJsEghlloksslktlNkslMkkstlolAtBsEkttsksolsssklslElllNslkpEtktskEgklEtkpsslkkoktpllsktkkskFlgslsoltlkllttlllkNlBNsltlJkgNpElthAtklststkktskktokkkskkkJEpkAllktktsFtstlkktlkklENhtoMllsttsAklskNhktltsIEtlskkltssslEMgslhktsNtkslkkllssllIgsskFEMghEllIEktNltkpkstskkksksslMthBNtlsENoMkkoMtslklkskksthgpktIklkkkJpkoNglEtpsstMklllkkktlstlFFItoMllEFkFkkoMtlklsspFsltItpMkItttEskAktlMtMsktsshkslkElIEElskNFhNoJlkssktlltktslpppNkpslhEtNlBJNBFklgslpEttFssklslklkJFMlhNklkEhMgMkMhglkFtksstkohkNlANkklsktllltsltkkssMklttlloJltkhpsgEoEtllotkhkpMtEtsttoMklloglsNEFEoFlllsktEkNsskslkkkllkktlhFtkhksJklslBFkkkklkkllgokFkhEktktsMhkJslssslkttlllFllkpEtlookgMIEolltskMllFkMMsktkkBlkshtgNhNtstEklslkollktstksklktENgFlllstkhglthsEFktklkkklNkMkstsklkgkpBAshsJtslEktNlllsskktskktENsthIolkMtlhklNktFlklssJktsElktkktkhEksskhlltkFkskgkskkosoBtttlFtMFgJtkJlkkkkllMsssskssoklssMsBFNstglksglllMssltkskElklltgssJtkklhktslkkktksslgsklpkkllglsltEEhkgJtssMlkkktstlhNtpBksskFMlAsIsklhkskkttMtslkskksllspstoMklllEElEgAsktshklktskkktEkssJtslMlkFootANlkltlsskltkkklkkskhokllBNssplltltkskloltktkkssEFsBlAtkstksklpFtsskkklkklkssNskBtllggtgsIkkttkktplhMtklIssNNkstlktMkktlABskEkslltklklskklllkElFkgFkkokgkskoMJFksMFkokktgNtktIklsostgktMkkltllstMkstFsktsEstlhkhspFktlFlsElpFsllttskkJklFNthtItEsEskklstAktkskstkksttMFAsolllgktpllotAlkkslttIkMskskkAlttskEFlptlltlllstFkksEtkklklktMNlkhMkktltlllNksssEkkkgtlkststlAEtlAkshkttItspklsslklstosgIkollFktFpBgtFNklJlttktlMklksNtltosplMlFphkllsstokktllkkkElksoBlksAtkkllMhtkFktlkksltEkkssktktkssghtBslNltkklkkstksksEkoFllsssEkJlAplslksIkshEsssklllslttollJlstshsksFklJktlkkskktkttMFhkpAstlMssAlpshNltpltlAllskskslkskgottkpFstlFltlsllltlsltlEkskMFsBpAksstsktlsFstNskllsMlkllEloJsltpokhFJNktJMllsltsklsslpNtIklllkIsskMgAkkoEskFtksklkktlkllEkEspMsklklFtsAlolkhssgkktltspltAMtkAklgkkFlkkptlstNkttEEskBktlhhlJEpElkkNltFsMsklFpkgttBtpFFlJsIkMFElkplkkIkttglstlltssMsBstEtlktpspNNsJsltAskoMtklEtttAkkktsEkslkltshkkkttlltlkkslEttkNMgkpMtttNtkskskltkNsklpklkNElEkEskkgttgkktpssssskkklsksNEBFssktJssJsEstANkkNlltskttkskkktllNsFtksthNkkskklpkkstsklkttltENtthIlltttotkoFtkEkstlkskttlktltksghkkpktsklspkkttskkstMMJklkllENMBhBtlJNktJklkMkNsklktslkssFkBspohMttslktIlltslkksllllMEkkpBksgkpFhFpNslktsstpolskkskktlktllsksgFllttMkskhkktkkstlElskMFsBoAlktotsplslktEtkkkttksststJMttkpFkpEgsslllkktltlkslMNAkkkkklltsAlpthElttkllBkkkstlskMkktkltkhIkktthkEv/EABQRAQAAAAAAAAAAAAAAAAAAAND/2gAIAQMBAT8QEAP/xAAhEQABAwQCAwEAAAAAAAAAAAABEVBgADBwgBBAICExkP/aAAgBAgEBPxCUr5ijQsfWZcAK4K7HJQo7eLsasRGeR3DDA/h/FkcDR5cTr0hKl1Y+TBGb3CvdgWEzQHc5xV4EIDqaFsP4xcYEKNgbwHrpqiNMlwQG9HhfMfkqsOWVreDae2slNCAjTEQAZtL4t9M0h2OADQ0vPIgAZD0DPBABAA7rfPHrNA8jpkJeaFleB0P/xAAnEAEAAgICAQMEAwEBAAAAAAABABEhMUFRYRBxgZGh0fCxweHxIP/aAAgBAQABPxCF3xHoV+N3zM4WUoN/4mVqq5H2ENK5u+mJBCsoERVRq+ImuQGwxfmIICFutNy5VZUBffMWEon/AIcSps3RLBQHo/8AFosDXheZijayq5YILQduotL34JawbpL6TdrldPOuZQLcv+oO7wE0us6jppoyf3KgzYG2Zq+9MJM+lOLYIliQcj1EGL3vP0grLIVD/kchaaPzBybUlQfQe0tgXW0uceYkgubr2f3mDanPmruZD2slfZlLDhjDhhdla1DXCC68xQLs4t/MW42rP8xwmpiujUiAKsdVF2sJtWiG3AbIBDmw3xmKkQDulgSy5W4NBavtHipwxaQdNu15inAGouom5Yl06IDv2qn5iUNpFcDQIxMTYspuIVKzdtreJRloD3ipfuGG1kclf1EVDeK6lC3wKJQS7FPPv+IGI0LrpliLIWXC6HE6Jdz9xz8kMOXRW/S1JsXcpUmHY6YlgKYa8wFrVM9nzEayToMfhCoFtu3r0QfRsFQNr+pcGjJeESi0nI0xFlqBmlJ6Mw1/Lv4hRS+138suL4N15gWnSXAq5Kbuj2nAdBXmPRSkzxmULoq2vaUQJXmuOmK3gNrLMinZhFFgaSN4weORGysGgun8RaALT8pkQlC93cRJhce1xT4GIT4BX78QexeJ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');
		background-position: center;
	}
	.textBoxWrapper {
		height: 9rem;
		width: inherit;
		background: #fff;
		position: relative;
		border-top: 1px solid #eee;
	}
	.messages {
		padding: 3rem;
		box-sizing: border-box;
		display: flex;
		flex-direction: column;
		justify-content: flex-end;
		width: inherit;
		min-height: 100%;
	}
	.chat {
		position: relative;
		width: 70%;
		background-color: #fff5f5;
		border-radius: 0.4rem;
		box-sizing: border-box;
		padding: 0.8rem 1.4rem;
		word-wrap: break-word;
		font-size: 15px;
		margin: 9px;
		min-height: 50px;
		height: auto;
	}
	.chat~.chat {
		margin-top: 2rem;
	}
	.chat.me {
		margin-left: auto;
		background-color: #5C6BC0;
		color: white;
	}
	.chat::after {
		content: '';
		display: block;
		width: 0;
		height: 0;
		border-width: 10px;
		border-style: solid;
		border-color: transparent;
		border-top-color:  #fff5f5;
		transform: skew(-45deg);
		position: absolute;
		top: 100%;
		left: 0;
		height: 50px;
	}
	.chat.me::after {
		border-top-color: #5C6BC0;
		right: 0;
		left: auto;
		transform: skew(45deg);
		height: 50px;
	}
		/* .chat:nth-child(even){
		  margin-left: auto;
		  background-color: #4e7e9a;
		  color: white;
		}
		*/

		.textbox {
			width: 100%;
			height: 100%;
			box-sizing: border-box;
			outline: none;
		}
		.buttonGroup {
			height: 4.5rem;
			position: absolute;
			right: 2rem;
			top: 50%;
			transform: translateY(-50%);
			display: flex;
			align-items: center;
		}
		.sendButton {
			background: ;
			width: 4.5rem;
			height: inherit;
			border-radius: 50%;
			background-position: center;
			background-size: 2.4rem;
			border: 0;
			cursor: pointer;
		}
		.textBoxContainer {
			height: calc(100% - 4rem);
			width: calc(100% - 10rem);
			box-sizing: border-box;
			transform: translateY(2rem);
			margin: 10px;
			padding: 5px;
			margin-top: -60px;
		}
		.emojiButton {
			width: 4rem;
			height: 4rem;
			border-radius: 50%;
			border: 0;
			background: url(https://cdn2.iconfinder.com/data/icons/picons-basic-1/57/basic1-119_smiley_neutral-512.png) no-repeat;
			background-size: 2rem;
			background-position: center;
			cursor: pointer;
		}
		.info~.chat, .chat~.info {
			margin-top: 2rem;
		}
		.info {
			background: rgb(173, 173, 173);
			width: 60%;
			padding: 0.7rem 0;
			border-radius: 0.4rem;
			text-align: center;
			margin: 0 auto;
			font-size: 0.8em;
		}
		.chatListHeader {
			background: white;
			height: 6rem;
			padding: 0 2rem;
		}
		.chatTitle {
			margin: 0;
			line-height: 6rem;
			font-weight: 400;
			font-weight: bold;
		}
		.chats {
			height: calc(100% - 12rem);
			overflow: auto;
			font-size: medium;
		}
		.chatUser {
			display: flex;
			padding: 1.2rem 0rem 6rem 1rem;
			cursor: pointer;
			height: 10%;
		}
		.chatUser~.chatUser {
			border-top: 1px solid #eee;
		}
		.chatUser--active {
			background: #eee !important;
		}
		.chatUser:hover {
			background: #b5b5b5;
			color: black;
		}
		.chatUserIcon {
			width: 4.5rem;
			height: 4.5rem;
			border-radius: 50%;
			background: #303F9F;
			color: white;
			overflow: hidden;
			text-align: center;
			line-height: 4.5rem;
		}
		.chatUserIcon img {
		    display: block;
    		width: 100%;
    		height: 100%;
		}
		.chatUserDetails {
			position: relative;
			flex-grow: 1;
			padding-left: 2rem;
			bottom: 10px;
		}
		.chatUsername {
			line-height: 4.5rem;
		}
		.lastMessageTime {
			position: absolute;
			bottom: 0;
			right: 0;
			font-size: 1rem;
		}
		.search {
			box-sizing: border-box;
			height: 3rem;
			margin-top: 1rem;
			margin-left: 1rem;
			margin-bottom: 1rem;
			width: calc(100% - 3rem);
			border-radius: 2rem;
			border: 1px solid #eee;
			padding: 0 2rem;
			font-size: 14px;
			font-family: inherit;
		}
		/* Logon*/

		.appLogon {
			background: white;
		}
		.left, .right {
			box-sizing: border-box;
			padding: 2rem 4rem;
		}
		.left {
			width: 35rem;
			background: #303F9F;
			color: white;
			display: flex;
			align-items: center;
			justify-content: center;
		}
		.right {
			width: 45rem;
			padding-left: 6rem;
			padding-right: 6rem;
		}
		.appInfo {
			text-align: center;
		}
		.appName {
			font-size: 3rem;
			font-weight: 400;
			margin: 0;
		}
		.tagline {
			margin: 0;
			font-weight: 1.2rem;
			margin-top: 1em;
		}
		.accountForm {
			margin-top: 3rem;
		}
		.formTitle {
			font-weight: 400;
		}
		.formInput {
			display: block;
		}
		.formInput~.formInput {
			margin-top: 2rem;
		}
		.formInputLabel {
			display: block;
			margin-bottom: 0.5rem;
		}
		.formUserInput {
			box-sizing: border-box;
			padding: 1rem 1.4rem;
			width: 100%;
			border-radius: 0.4rem;
			border: 0.2rem solid #aaa;
			font-size: 1.4em;
			color: inherit;
		}
		.formUserInput:focus {
			border-color: #333;
		}
		.btn-chat {
			color: white;
			padding: 1.2rem 2rem;
			border: 0;
			top: 10px;
			left: 15px;
			border-radius: 100px;
		}
		.additionalInfo {
			margin-top: 4rem;
		}
		.accountLink {
			color: #E65100;
			text-decoration: none;
			padding: 0.5rem 0;
			border-bottom: 0.1rem dotted transparent;
		}
		.createAccountLink:hover {
			border-color: #E65100;
			color: #E65100;
		}
		/* .popUps */

		.popUps {
			width: 30rem;
			position: fixed;
			bottom: 10rem;
			left: 9rem;
			z-index: 1;
		}
		.notification {
			background: white;
			padding: 2rem;
			border-radius: 0.4rem;
			box-shadow: 0 0.1rem 3rem rgba(0, 0, 0, 0.3);
		}
		.notification~.notification {
			margin-top: 1.5rem;
		}
		/* .topLeft */

		.topLeft {
			position: fixed;
			top: 4rem;
			left: 9rem;
		}
		/* .topRight */

		.topRight {
			position: fixed;
			top: 4rem;
			right: 9rem;
		}
		.hamburger {
			display: block;
			width: 60px;
			height: 60px;
			background: transparent;
			border: none;
			position: absolute;
		}
		.hamburgerIcon {
			stroke: white;
		}
		@media (min-width: 426px) {
			.hamburger {
				display: none;
			}
			.chatList {
				position: relative;
				width: 30%;
				transform: translateX(0);
			}
			.chatDetails {
				flex: 1;
				margin-left: 1px solid #eee;
			}
		}
</style>