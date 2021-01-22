var forms = new Array;

function contains(valor, array)
{
	var cont;
	
	for (cont = 0; cont <= array.length - 1; cont = cont + 1)
	{
		if (array[cont].toString().toLowerCase() === valor.toString().toLowerCase())
		{
			return true;
		}
	}
	
	return false;
}

function QunatidadeCamposCorretos(form)
{
	var cont;
	var campos = form.getElementsByClassName("at-valida");
	campos = Array.prototype.slice.call(campos);
	
	var camposCorretos = 0;
	
	for (cont = 0; cont <= campos.length - 1; cont = cont + 1)
	{
		var campo = campos[cont];
		
		if (campo.getAttribute('correto') == 1)
		{
			camposCorretos = camposCorretos + 1;
		}
	}
	
	return camposCorretos;
}

function VerificarForm(form)
{
	AplicarEventos(form);
	
	var cont;
	var campos = form.getElementsByClassName("at-valida");
	campos = Array.prototype.slice.call(campos);
	
	var totalCamposObrigatorios = 0;
	var camposCorretos = 0;
	
	for (cont = 0; cont <= campos.length - 1; cont = cont + 1)
	{
		var campo = campos[cont];
		
		if (campo.required == true)
		{
			totalCamposObrigatorios = totalCamposObrigatorios + 1;
		}
	}
	
	for (cont = 0; cont <= campos.length - 1; cont = cont + 1)
	{
		var campo = campos[cont];
		
		try
		{
			campo.onblur();
		}
		catch (exe)
		{
			console.log(campo);
			console.log(exe.message);
		}
		
		camposCorretos = camposCorretos + parseInt(campo.getAttribute("correto"));
	}
	
	if (camposCorretos >= totalCamposObrigatorios)
	{
		return true;
	}
	else
	{
		return false;
	}
}

function MapearForms()
{
	var body = document.getElementsByTagName('body')[0];
	var forms = body.getElementsByTagName('form');
	var cont;
	
	for (cont = 0; cont <= forms.length - 1; cont = cont + 1)
	{
		AplicarEventos(forms[cont]);
	}
}

function AplicarEventos(form)
{
	var campos = form.getElementsByClassName("at-valida");
	campos = Array.prototype.slice.call(campos);
	
	forms.push
	(
		{
			form: form,
			campos: new Array(campos)
		}
	);
	
	for (cont = 0; cont <= campos.length - 1; cont = cont + 1)
	{
		try
		{
			var atr = campos[cont].getAttribute("tipo").toString();
			campos[cont].setAttribute("correto", 0);
		}
		catch (exe)
		{
			var atr = "";
		}
		
		if (atr == "NomeCompleto")
		{
			campos[cont].maxLength = 50;
			
			campos[cont].onblur = function()
			{
				var valorCampo = this.value;
				var valorFiltrado = "";
				var filtro = "abcdefghijlmnopqrstuvxzwykçáàâãéèêíìîóòõôúùûQWERTYUIOPASDFGHJKLÇZXCVBNMÁÀÂÃÉÈÊÍÌÎÓÒÕÔÚÙÛ ";
					
				for (cont = 0; cont <= valorCampo.length - 1; cont = cont + 1)
				{
					if (filtro.indexOf(valorCampo.charAt(cont)) != -1)
					{
						valorFiltrado = valorFiltrado + valorCampo.charAt(cont);
					}
				}
				
				this.value = valorFiltrado;
				
				var tamanho, cont;
				var palavra, copia = "";
				
				this.value = this.value.trim();
				
				if (this.value != "")
				{
					tamanho = this.value.length;
					palavra = this.value;
					
					for (cont = 0; cont <= tamanho - 1; cont = cont + 1)
					{
						if (cont == 0)
						{
							copia = copia + palavra.charAt(cont).toUpperCase();
						}
						else if (palavra.charAt(cont) == " ")
						{
							if (palavra.charAt(cont + 1) != " ")
							{
								copia = copia + " " + palavra.charAt(cont + 1).toUpperCase();
								cont = cont + 1;
							}
						}
						else
						{
							copia = copia + palavra.charAt(cont).toLowerCase();
						}
					}
					
					this.value = copia;
					
					if (this.value.indexOf(" ") == -1)
					{
						this.setAttribute("correto", 0);
					}
					else
					{
						this.setAttribute("correto", 1);
					}
				}
				else
				{
					this.setAttribute("correto", 0);
				}
			}
			
			campos[cont].onkeypress = function(e,args)
			{
				if (document.all) // caso seja IE
				{
					var evt=event.keyCode;
				}			
				else // do contrário deve ser Mozilla ou Google
				{
					var evt = e.charCode;
				}
				
				var valid_chars = 'abcdefghijlmnopqrstuvxzwykçáàâãéèêíìîóòõôúùû QWERTYUIOPASDFGHJKLÇZXCVBNMÁÀÂÃÉÈÊÍÌÎÓÒÕÔÚÙÛ´`~^';      // criando a lista de teclas permitidas
				var chr= String.fromCharCode(evt);      // pegando a tecla digitada
				
				if (valid_chars.indexOf(chr)>-1 ) // se a tecla estiver na lista de permissão permite-a
				{
					return true;
				}
				
				// para permitir teclas como <BACKSPACE> adicionamos uma permissão para
				// códigos de tecla menores que 09 por exemplo 
				
				if (valid_chars.indexOf(chr)>-1 || evt < 9)
				{
					return true;
				}
				
				return false;   // do contrário nega
			}
		}
		else if (atr == "E-mail")
		{
			campos[cont].maxLength = 50;
			
			campos[cont].onkeypress = function filtroEmail(e,args)
			{              
				if (document.all) // caso seja IE
				{
					var evt = event.keyCode;
				}			
				else // do contrário deve ser Mozilla ou Google
				{
					var evt = e.charCode;
				}
				
				var valid_chars = '0123456789abcdefghijlmnopqrstuvxzwykQWERTYUIOPASDFGHJKLZXCVBNM-_@.';      // criando a lista de teclas permitidas
				var chr = String.fromCharCode(evt);      // pegando a tecla digitada
				
				if (chr == '@' && this.value.indexOf('@') != -1)
				{
					return false;
				}
				
				if (valid_chars.indexOf(chr)>-1 || evt < 9)
				{
					return true;
				}
				
				return false;   // do contrário nega
			}
			
			campos[cont].onblur = function()
			{
				var valorCampo = this.value;
				var valorFiltrado = "";
				var quant = 0;
				var filtro = "1234567890qwertyuiopasdfghjklzxcvbnmQWERTYUIOPLKJHGFDSAZXCVBNM@.-_";
					
				for (cont = 0; cont <= valorCampo.length - 1; cont = cont + 1)
				{
					if (filtro.indexOf(valorCampo.charAt(cont)) != -1)
					{
						valorFiltrado = valorFiltrado + valorCampo.charAt(cont).toLowerCase();
					}
				}

				this.value = valorFiltrado;
				
				while ((this.value.lastIndexOf(".") == this.value.length - 1) && (this.value.lastIndexOf(".") != -1 && this.value.length != 0))
				{
					aux = this.value.split("");
					aux[this.value.length - 1] = "";
					outraaux = "";
					
					for (cont = 0; cont <= this.value.length - 1; cont = cont +1)
					{
						outraaux = outraaux + aux[cont];
					}
					
					this.value = outraaux;
				}
				
				if (this.value.indexOf("@") != -1)
				{
					var aux = this.value.split("@");
					var usuario = aux[0];
					var dominio = aux[1];
					
					if ((usuario.length >= 1) && 
						(dominio.length >= 3) && 
						(dominio.indexOf(".") != -1) &&
						(dominio.indexOf(".") >= 1) &&
						(dominio.lastIndexOf(".") < dominio.length - 1))
					{
						this.setAttribute("correto", 1);
					}
					else
					{
						this.setAttribute("correto", 0);
					}
				}
				else
				{
					this.setAttribute("correto", 0);
				}
			}
		}
		else if (atr == "Senha" || atr == "NovaSenha")
		{
			var minimo = 6;
			var maximo = 16
			
			campos[cont].maxLength = maximo;
			
			campos[cont].onkeypress = function(e,args)
			{
				if (document.all) // caso seja IE
				{
					var evt=event.keyCode;
				}			
				else // do contrário deve ser Mozilla ou Google
				{
					var evt = e.charCode;
				}
				
				var valid_chars = "0123456789abcdefghijlmnopqrstuvxzwykçÇsQWERTYUIOPASDFGHJKLZXCVBNM´`~!@#$%^&*()_-+={}[]\\|:;<>,.?/";      // criando a lista de teclas permitidas
				var chr= String.fromCharCode(evt);      // pegando a tecla digitada
				
				if (valid_chars.indexOf(chr)>-1 ) // se a tecla estiver na lista de permissão permite-a
				{
					return true;
				}
				
				// para permitir teclas como <BACKSPACE> adicionamos uma permissão para
				// códigos de tecla menores que 09 por exemplo 
				
				if (valid_chars.indexOf(chr)>-1 || evt < 9)
				{
					return true;
				}
				
				return false;   // do contrário nega
			}
			
			campos[cont].onblur = function()
			{
				var valorCampo = this.value;
				var valorFiltrado = "";
				var quant = 0;
				var filtro = "0123456789abcdefghijlmnopqrstuvxzwykçÇsQWERTYUIOPASDFGHJKLZXCVBNM´`~!@#$%^&*()_-+={}[]\\|:;<>,.?/";
					
				for (cont = 0; cont <= valorCampo.length - 1; cont = cont + 1)
				{
					if (filtro.indexOf(valorCampo.charAt(cont)) != -1)
					{
						valorFiltrado = valorFiltrado + valorCampo.charAt(cont);
					}
				}
				
				this.value = valorFiltrado;
				
				if (this.value.length >= minimo && this.value.length <= maximo)
				{
					this.setAttribute("correto", 1);
				}
				else
				{
					this.setAttribute("correto", 0);
				}
			}
		}
		else if (atr == "ConfirmarSenha")
		{
			for (linha = 0; linha <= forms.length - 1; linha = linha + 1)
			{
				if (forms[linha].campos[0].indexOf(campos[cont]) != -1)
				{
					for (coluna = 0; coluna <= forms[linha].campos[0].length - 1; coluna = coluna + 1)
					{
						if (forms[linha].campos[0][coluna].getAttribute("tipo") == "Senha")
						{
							var campoSenha = forms[linha].campos[0][coluna];
							
							var minimo = 6;
							var maximo = 16
							campos[cont].maxLength = maximo;
							
							campos[cont].onkeypress = function(e,args)
							{
								if (document.all) // caso seja IE
								{
									var evt=event.keyCode;
								}			
								else // do contrário deve ser Mozilla ou Google
								{
									var evt = e.charCode;
								}
								
								var valid_chars = "0123456789abcdefghijlmnopqrstuvxzwykçÇsQWERTYUIOPASDFGHJKLZXCVBNM´`~!@#$%^&*()_-+={}[]\\|:;<>,.?/";      // criando a lista de teclas permitidas
								var chr= String.fromCharCode(evt);      // pegando a tecla digitada
								
								if (valid_chars.indexOf(chr)>-1 ) // se a tecla estiver na lista de permissão permite-a
								{
									return true;
								}
								
								// para permitir teclas como <BACKSPACE> adicionamos uma permissão para
								// códigos de tecla menores que 09 por exemplo 
								
								if (valid_chars.indexOf(chr)>-1 || evt < 9)
								{
									return true;
								}
								
								return false;   // do contrário nega
							}
							
							campos[cont].onblur = function()
							{
								var valorCampo = this.value;
								var valorFiltrado = "";
								var quant = 0;
								var filtro = "0123456789abcdefghijlmnopqrstuvxzwykçÇsQWERTYUIOPASDFGHJKLZXCVBNM´`~!@#$%^&*()_-+={}[]\\|:;<>,.?/";
									
								for (cont = 0; cont <= valorCampo.length - 1; cont = cont + 1)
								{
									if (filtro.indexOf(valorCampo.charAt(cont)) != -1)
									{
										valorFiltrado = valorFiltrado + valorCampo.charAt(cont);
									}
								}
								
								this.value = valorFiltrado;
								
								if (this.value == campoSenha.value && campoSenha.value != "")
								{
									this.setAttribute("correto", 1);
								}
								else
								{
									this.setAttribute("correto", 0);
								}
							}
							
							break;
						}
					}
					
					break;
				}
			}
		}
		else if (atr.toLowerCase() == "combobox")
		{
			campos[cont].onblur = function()
			{
				if (this.value != "")
				{
					this.setAttribute("correto", 1);
				}
				else
				{
					this.setAttribute("correto", 0);
				}
			}
		}
		else if (atr == "Dia")
		{
			campos[cont].maxLength = 2;
			
			campos[cont].onkeypress = function(e,args)
			{
				if (document.all) // caso seja IE
				{
					var evt=event.keyCode;
				}			
				else // do contrário deve ser Mozilla ou Google
				{
					var evt = e.charCode;
				}
				
				var valid_chars = "1234567890";      // criando a lista de teclas permitidas
				var chr= String.fromCharCode(evt);      // pegando a tecla digitada
				
				if (valid_chars.indexOf(chr)>-1 ) // se a tecla estiver na lista de permissão permite-a
				{
					return true;
				}
				
				// para permitir teclas como <BACKSPACE> adicionamos uma permissão para
				// códigos de tecla menores que 09 por exemplo 
				
				if (valid_chars.indexOf(chr)>-1 || evt < 9)
				{
					return true;
				}
				
				return false;   // do contrário nega
			}
			
			campos[cont].onblur = function()
			{
				var valorCampo = this.value;
				var valorFiltrado = "";
				var quant = 0;
				var filtro = "1234567890";
					
				for (cont = 0; cont <= valorCampo.length - 1; cont = cont + 1)
				{
					if (filtro.indexOf(valorCampo.charAt(cont)) != -1)
					{
						valorFiltrado = valorFiltrado + valorCampo.charAt(cont);
					}
				}
				
				this.value = valorFiltrado;
				
				if (this.value.length > 0)
				{
					this.setAttribute("correto", 1);
				}
				else
				{
					this.setAttribute("correto", 0);
				}
			}
		}
		else if (atr == "Ano")
		{
			campos[cont].maxLength = 4;
			
			campos[cont].onkeypress = function(e,args)
			{
				if (document.all) // caso seja IE
				{
					var evt=event.keyCode;
				}			
				else // do contrário deve ser Mozilla ou Google
				{
					var evt = e.charCode;
				}
				
				var valid_chars = "1234567890";      // criando a lista de teclas permitidas
				var chr= String.fromCharCode(evt);      // pegando a tecla digitada
				
				if (valid_chars.indexOf(chr)>-1 ) // se a tecla estiver na lista de permissão permite-a
				{
					return true;
				}
				
				// para permitir teclas como <BACKSPACE> adicionamos uma permissão para
				// códigos de tecla menores que 09 por exemplo 
				
				if (valid_chars.indexOf(chr)>-1 || evt < 9)
				{
					return true;
				}
				
				return false;   // do contrário nega
			}
			
			campos[cont].onblur = function()
			{
				var valorCampo = this.value;
				var valorFiltrado = "";
				var quant = 0;
				var filtro = "1234567890";
					
				for (cont = 0; cont <= valorCampo.length - 1; cont = cont + 1)
				{
					if (filtro.indexOf(valorCampo.charAt(cont)) != -1)
					{
						valorFiltrado = valorFiltrado + valorCampo.charAt(cont);
					}
				}
				
				this.value = valorFiltrado;
				
				if (this.value.length > 0)
				{
					this.setAttribute("correto", 1);
				}
				else
				{
					this.setAttribute("correto", 0);
				}
			}
		}
		else if (atr == "Datanas")
		{
			campos[cont].maxLength = 10;
			
			campos[cont].onkeypress = function(e,args)
			{
				if (document.all) // caso seja IE
				{
					var evt=event.keyCode;
				}			
				else // do contrário deve ser Mozilla ou Google
				{
					var evt = e.charCode;
				}
				
				var valid_chars = '1234567890/';      // criando a lista de teclas permitidas
				var chr= String.fromCharCode(evt);      // pegando a tecla digitada
				
				if (valid_chars.indexOf(chr)>-1 || evt < 9)
				{
					var valorCampo = this.value;
					var valorFiltrado = "";
					var quant = 0;
					
					var index = valid_chars.indexOf(chr);
					var tecla = valid_chars.charAt(index);
					
					var tamanho = valorCampo.length;
					
					if (tecla == '/' && (tamanho == 2 || tamanho == 5))
					{
						return true;
					}
					
					if (tecla >= 0)
					{
						if (tamanho == 2 || tamanho == 5)
						{
							valorCampo = mascaraData(valorCampo);
							this.value = valorCampo;
							return true;
						}
						else
						{
							return true;
						}
					}
				}
				
				return false;   // do contrário nega
			}
			
			campos[cont].onblur = function()
			{
				eval(this.getAttribute("antesOnBlur"));
				
				var valorCampo = this.value;
				var valorFiltrado = "";
				var quant = 0;
				
				aux = mascaraData(valorCampo);
				valorCampo = aux;
				
				for (cont = 0; cont <= valorCampo.length - 1; cont = cont + 1)
				{
					if (valorCampo.charAt(cont) >= 0 && valorCampo.charAt(cont) <= 9 && valorCampo.charAt(cont) != " ")
					{
						valorFiltrado = valorFiltrado + valorCampo.charAt(cont);
					}
					
					if (valorCampo.charAt(cont) == '/' && (cont == 2 || cont == 5))
					{
						valorFiltrado = valorFiltrado + valorCampo.charAt(cont);
					}
				}
				this.value = valorFiltrado;
				
				aux = this.value;
				aux =  aux.replace('/', "");
				aux =  aux.replace('/', "");
				
				if (aux.indexOf("-") == -1 && aux.length == 8)
				{
					var separa = aux;
						separa = separa.split("",8);
					var dia = separa[0] + separa[1];
						dia = parseInt(dia);
					var mes = separa[2] + separa[3];
						mes = parseInt(mes);
					var ano = separa[4] + separa[5] + separa[6] + separa[7];
						ano = parseInt(ano);
					var bi4 = ano;
						bi4 = bi4%4;
						bi4 = parseInt(bi4);
					var bi400 = ano;
						bi400 = bi400%400;
						bi400 = parseInt(bi400);
					var bi100 = ano;
						bi100 = bi100%100;
						bi100 = parseInt(bi100);
					
					var hoje = new Date();
					var anoAtual = hoje.getFullYear();
					var mesAtual = hoje.getMonth() + 1;
					var diaAtual = hoje.getDate();
					var idade;
					
					if ((mesAtual >= mes) && (diaAtual >= dia))
					{
						idade = anoAtual - ano;
					}
					else
					{
						idade = anoAtual - ano - 1;
					}
					
					if ((dia >= 1 && dia <= 31) && (mes >= 1 && mes <= 12))
					{
						if (((bi4 == 0 && bi100 != 0) || (bi100 == 0 && bi400 == 0)))
						{
							if (((mes == 2 && dia <= 29) || (mes == 1 || mes == 3 || mes == 5 || mes == 7 ||  mes == 8 || mes == 10 || mes == 12 && dia <= 31) || (mes == 4 || mes == 6 || mes == 9 || mes == 11 && dia <= 30)) && (idade >= 15 && idade <= 80))
							{
								this.setAttribute("correto", 1);
							}
							else
							{
								this.setAttribute("correto", 0);
							}
						}
						else
						{
							if (((mes == 2 && dia <= 28) || (mes == 1 || mes == 3 || mes == 5 || mes == 7 || mes == 8 ||  mes == 10 || mes == 12 && dia <= 31) || (mes == 4 || mes == 6 ||mes == 9 || mes == 11 && dia <= 30)) && (idade >= 15 && idade <= 80))
							{
								this.setAttribute("correto", 1);
							}
							else
							{
								this.setAttribute("correto", 0);
							}
						}
					}
					else	
					{
						this.setAttribute("correto", 0);
					}
				}
				else
				{
					this.setAttribute("correto", 0);
				}
			}
		}
		else if (atr == "Data")
		{
			campos[cont].maxLength = 10;
			
			campos[cont].onkeypress = function(e,args)
			{
				if (document.all) // caso seja IE
				{
					var evt=event.keyCode;
				}			
				else // do contrário deve ser Mozilla ou Google
				{
					var evt = e.charCode;
				}
				
				var valid_chars = '1234567890/';      // criando a lista de teclas permitidas
				var chr= String.fromCharCode(evt);      // pegando a tecla digitada
				
				if (valid_chars.indexOf(chr)>-1 || evt < 9)
				{
					var valorCampo = this.value;
					var valorFiltrado = "";
					var quant = 0;
					
					var index = valid_chars.indexOf(chr);
					var tecla = valid_chars.charAt(index);
					
					var tamanho = valorCampo.length;
					
					if (tecla == '/' && (tamanho == 2 || tamanho == 5))
					{
						return true;
					}
					
					if (tecla >= 0)
					{
						if (tamanho == 2 || tamanho == 5)
						{
							valorCampo = mascaraData(valorCampo);
							this.value = valorCampo;
							return true;
						}
						else
						{
							return true;
						}
					}
				}
				
				return false;   // do contrário nega
			}
			
			campos[cont].onblur = function()
			{
				eval(this.getAttribute("antesOnBlur"));
				
				var valorCampo = this.value;
				var valorFiltrado = "";
				var quant = 0;
				
				aux = mascaraData(valorCampo);
				valorCampo = aux;
				
				for (cont = 0; cont <= valorCampo.length - 1; cont = cont + 1)
				{
					if (valorCampo.charAt(cont) >= 0 && valorCampo.charAt(cont) <= 9 && valorCampo.charAt(cont) != " ")
					{
						valorFiltrado = valorFiltrado + valorCampo.charAt(cont);
					}
					
					if (valorCampo.charAt(cont) == '/' && (cont == 2 || cont == 5))
					{
						valorFiltrado = valorFiltrado + valorCampo.charAt(cont);
					}
				}
				this.value = valorFiltrado;
				
				aux = this.value;
				aux =  aux.replace('/', "");
				aux =  aux.replace('/', "");
				
				if (aux.indexOf("-") == -1 && aux.length == 8)
				{
					var separa = aux;
						separa = separa.split("",8);
					var dia = separa[0] + separa[1];
						dia = parseInt(dia);
					var mes = separa[2] + separa[3];
						mes = parseInt(mes);
					var ano = separa[4] + separa[5] + separa[6] + separa[7];
						ano = parseInt(ano);
					var bi4 = ano;
						bi4 = bi4%4;
						bi4 = parseInt(bi4);
					var bi400 = ano;
						bi400 = bi400%400;
						bi400 = parseInt(bi400);
					var bi100 = ano;
						bi100 = bi100%100;
						bi100 = parseInt(bi100);
					
					if ((dia >= 1 && dia <= 31) && (mes >= 1 && mes <= 12))
					{
						if (((bi4 == 0 && bi100 != 0) || (bi100 == 0 && bi400 == 0)))
						{
							if (((mes == 2 && dia <= 29) || (mes == 1 || mes == 3 || mes == 5 || mes == 7 ||  mes == 8 || mes == 10 || mes == 12 && dia <= 31) || (mes == 4 || mes == 6 || mes == 9 || mes == 11 && dia <= 30)))
							{
								this.setAttribute("correto", 1);
							}
							else
							{
								this.setAttribute("correto", 0);
							}
						}
						else
						{
							if (((mes == 2 && dia <= 28) || (mes == 1 || mes == 3 || mes == 5 || mes == 7 || mes == 8 ||  mes == 10 || mes == 12 && dia <= 31) || (mes == 4 || mes == 6 ||mes == 9 || mes == 11 && dia <= 30)))
							{
								this.setAttribute("correto", 1);
							}
							else
							{
								this.setAttribute("correto", 0);
							}
						}
					}
					else	
					{
						this.setAttribute("correto", 0);
					}
				}
				else
				{
					this.setAttribute("correto", 0);
				}
			}
		}
		else if (atr == "Nickname")
		{
			campos[cont].maxLength = 15;
			
			campos[cont].onblur = function()
			{
				var valorCampo = this.value;
				var valorFiltrado = "";
				var filtro = "abcdefghijlmnopqrstuvxzwykQWERTYUIOPASDFGHJKLÇZXCVBNM1234567890";
					
				for (cont = 0; cont <= valorCampo.length - 1; cont = cont + 1)
				{
					if (filtro.indexOf(valorCampo.charAt(cont)) != -1)
					{
						valorFiltrado = valorFiltrado + valorCampo.charAt(cont);
					}
				}
				
				this.value = valorFiltrado;
				
				if (this.value == "")
				{
					this.setAttribute("correto", 0);
				}
				else
				{
					this.setAttribute("correto", 1);
				}
			}
			
			campos[cont].onkeypress = function(e,args)
			{
				if (document.all) // caso seja IE
				{
					var evt=event.keyCode;
				}			
				else // do contrário deve ser Mozilla ou Google
				{
					var evt = e.charCode;
				}
				
				var valid_chars = 'abcdefghijlmnopqrstuvxzwykQWERTYUIOPASDFGHJKLÇZXCVBNM1234567890';      // criando a lista de teclas permitidas
				var chr= String.fromCharCode(evt);      // pegando a tecla digitada
				
				if (valid_chars.indexOf(chr)>-1 ) // se a tecla estiver na lista de permissão permite-a
				{
					return true;
				}
				
				// para permitir teclas como <BACKSPACE> adicionamos uma permissão para
				// códigos de tecla menores que 09 por exemplo 
				
				if (valid_chars.indexOf(chr)>-1 || evt < 9)
				{
					return true;
				}
				
				return false;   // do contrário nega
			}
		}
		else if (atr == "Celular")
		{
			campos[cont].maxLength = 15;
			
			campos[cont].onkeypress = function(e,args)
			{
				if (document.all) // caso seja IE
				{
					var evt=event.keyCode;
				}			
				else // do contrário deve ser Mozilla ou Google
				{
					var evt = e.charCode;
				}
				
				var valid_chars = '1234567890()- ';      // criando a lista de teclas permitidas
				var chr= String.fromCharCode(evt);      // pegando a tecla digitada
				
				if (valid_chars.indexOf(chr)>-1 || evt < 9)
				{
					var valorCampo = this.value;
					var valorFiltrado = "";
					var quant = 0;
					
					var index = valid_chars.indexOf(chr);
					var tecla = valid_chars.charAt(index);
					
					var tamanho = valorCampo.length;
					
					
					if (tecla == '(' && tamanho == 0)
					{
						return true;
					}
					else if (tecla == ')' && tamanho == 3)
					{
						return true;
					}
					else if (tecla == " " && tamanho == 4)
					{
						return true;
					}
					else if (tecla == '-' && tamanho == 10)
					{
						return true;
					}
					else if (tecla >= 0)
					{
						if (tamanho == 0 || tamanho == 3 || tamanho == 4 || tamanho == 10)
						{
							valorCampo = mascaraCelular(valorCampo);
							this.value = valorCampo;
							return true;
						}
						else
						{
							return true;
						}
					}
				}
				
				return false;   // do contrário nega
			}
			
			campos[cont].onblur = function()
			{
				var valorCampo = this.value;
				var valorFiltrado = "";
				var quant = 0;
				
				aux = mascaraCelular(valorCampo);
				valorCampo = aux;
				
				for (cont = 0; cont <= valorCampo.length - 1; cont = cont + 1)
				{
					if (valorCampo.charAt(cont) >= 0 && valorCampo.charAt(cont) <= 9 && valorCampo.charAt(cont) != " ")
					{
						valorFiltrado = valorFiltrado + valorCampo.charAt(cont);
					}
					
					if (valorCampo.charAt(cont) == '(' && cont == 0)
					{
						valorFiltrado = valorFiltrado + valorCampo.charAt(cont);
					}
					
					if (valorCampo.charAt(cont) == ')' && cont == 3)
					{
						valorFiltrado = valorFiltrado + valorCampo.charAt(cont);
					}
					
					if (valorCampo.charAt(cont) == " " && cont == 4)
					{
						valorFiltrado = valorFiltrado + valorCampo.charAt(cont);
					}
					
					if (valorCampo.charAt(cont) == "-" && cont == 10)
					{
						valorFiltrado = valorFiltrado + valorCampo.charAt(cont);
					}
				}
				this.value = valorFiltrado;
				
				aux = this.value;
				aux =  aux.replace('(', "");
				aux =  aux.replace(')', "");
				aux =  aux.replace(" ", "");
				aux =  aux.replace('-', "");
				
				if (aux.length == 10 || aux.length == 11)
				{
					this.setAttribute("correto", 1);
				}
				else
				{
					this.setAttribute("correto", 0);
				}
			}
		}
		else if (atr == "Telefone" || atr == "Fixo")
		{
			campos[cont].maxLength = 14;
			
			campos[cont].onkeypress = function(e,args)
			{
				if (document.all) // caso seja IE
				{
					var evt=event.keyCode;
				}			
				else // do contrário deve ser Mozilla ou Google
				{
					var evt = e.charCode;
				}
				
				var valid_chars = '1234567890()- ';      // criando a lista de teclas permitidas
				var chr= String.fromCharCode(evt);      // pegando a tecla digitada
				
				if (valid_chars.indexOf(chr)>-1 || evt < 9)
				{
					var valorCampo = this.value;
					var valorFiltrado = "";
					var quant = 0;
					
					var index = valid_chars.indexOf(chr);
					var tecla = valid_chars.charAt(index);
					
					var tamanho = valorCampo.length;
					
					
					if (tecla == '(' && tamanho == 0)
					{
						return true;
					}
					else if (tecla == ')' && tamanho == 3)
					{
						return true;
					}
					else if (tecla == " " && tamanho == 4)
					{
						return true;
					}
					else if (tecla == '-' && tamanho == 9)
					{
						return true;
					}
					else if (tecla >= 0)
					{
						if (tamanho == 0 || tamanho == 3 || tamanho == 4 || tamanho == 9)
						{
							valorCampo = mascaraFixo(valorCampo);
							this.value = valorCampo;
							return true;
						}
						else
						{
							return true;
						}
					}
				}
				
				return false;   // do contrário nega
			}
			
			campos[cont].onblur = function()
			{
				var valorCampo = this.value;
				var valorFiltrado = "";
				var quant = 0;
				
				aux = mascaraFixo(valorCampo);
				valorCampo = aux;
				
				for (cont = 0; cont <= valorCampo.length - 1; cont = cont + 1)
				{
					if (valorCampo.charAt(cont) >= 0 && valorCampo.charAt(cont) <= 9 && valorCampo.charAt(cont) != " ")
					{
						valorFiltrado = valorFiltrado + valorCampo.charAt(cont);
					}
					
					if (valorCampo.charAt(cont) == '(' && cont == 0)
					{
						valorFiltrado = valorFiltrado + valorCampo.charAt(cont);
					}
					
					if (valorCampo.charAt(cont) == ')' && cont == 3)
					{
						valorFiltrado = valorFiltrado + valorCampo.charAt(cont);
					}
					
					if (valorCampo.charAt(cont) == " " && cont == 4)
					{
						valorFiltrado = valorFiltrado + valorCampo.charAt(cont);
					}
					
					if (valorCampo.charAt(cont) == "-" && cont == 9)
					{
						valorFiltrado = valorFiltrado + valorCampo.charAt(cont);
					}
				}
				this.value = valorFiltrado;
				
				aux = this.value;
				aux =  aux.replace('(', "");
				aux =  aux.replace(')', "");
				aux =  aux.replace(" ", "");
				aux =  aux.replace('-', "");
				
				if (aux.length == 10)
				{
					this.setAttribute("correto", 1);
				}
				else
				{
					this.setAttribute("correto", 0);
				}
			}
		}
		else if (atr == "Nome")
		{
			campos[cont].maxLength = 50;
			
			campos[cont].onblur = function()
			{
				var valorCampo = this.value;
				var valorFiltrado = "";
				var filtro = "abcdefghijlmnopqrstuvxzwykçáàâãéèêíìîóòõôúùûQWERTYUIOPASDFGHJKLÇZXCVBNMÁÀÂÃÉÈÊÍÌÎÓÒÕÔÚÙÛ ";
					
				for (cont = 0; cont <= valorCampo.length - 1; cont = cont + 1)
				{
					if (filtro.indexOf(valorCampo.charAt(cont)) != -1)
					{
						valorFiltrado = valorFiltrado + valorCampo.charAt(cont);
					}
				}
				
				this.value = valorFiltrado;
				this.value = this.value.trim();
				
				if (this.value != "")
				{
					this.setAttribute("correto", 1);
				}
				else
				{
					this.setAttribute("correto", 0);
				}
			}
			
			campos[cont].onkeypress = function(e,args)
			{
				if (document.all) // caso seja IE
				{
					var evt=event.keyCode;
				}			
				else // do contrário deve ser Mozilla ou Google
				{
					var evt = e.charCode;
				}
				
				var valid_chars = 'abcdefghijlmnopqrstuvxzwykçáàâãéèêíìîóòõôúùû QWERTYUIOPASDFGHJKLÇZXCVBNMÁÀÂÃÉÈÊÍÌÎÓÒÕÔÚÙÛ´`~^';      // criando a lista de teclas permitidas
				var chr= String.fromCharCode(evt);      // pegando a tecla digitada
				
				if (valid_chars.indexOf(chr)>-1 ) // se a tecla estiver na lista de permissão permite-a
				{
					return true;
				}
				
				return false;   // do contrário nega
			}
		}
		else if (atr == "Endereço" || atr == "Endereco")
		{
			campos[cont].maxLength = 100;
			
			campos[cont].onblur = function()
			{
				var valorCampo = this.value;
				var valorFiltrado = "";
				var filtro = "1234567890abcdefghijlmnopqrstuvxzwykçáàâãéèêíìîóòõôúùûQWERTYUIOPASDFGHJKLÇZXCVBNMÁÀÂÃÉÈÊÍÌÎÓÒÕÔÚÙÛ ,ªº/.()-&";
					
				for (cont = 0; cont <= valorCampo.length - 1; cont = cont + 1)
				{
					if (filtro.indexOf(valorCampo.charAt(cont)) != -1)
					{
						valorFiltrado = valorFiltrado + valorCampo.charAt(cont);
					}
				}
				
				this.value = valorFiltrado;
				this.value = this.value.trim();
				
				if (this.value != "" && this.value.indexOf(',') != -1)
				{
					this.setAttribute("correto", 1);
				}
				else
				{
					this.setAttribute("correto", 0);
				}
			}
			
			campos[cont].onkeypress = function(e,args)
			{
				if (document.all) // caso seja IE
				{
					var evt=event.keyCode;
				}			
				else // do contrário deve ser Mozilla ou Google
				{
					var evt = e.charCode;
				}
				
				var valid_chars = '1234567890abcdefghijlmnopqrstuvxzwykçáàâãéèêíìîóòõôúùûQWERTYUIOPASDFGHJKLÇZXCVBNMÁÀÂÃÉÈÊÍÌÎÓÒÕÔÚÙÛ ,ªº/.()-&´`~^';      // criando a lista de teclas permitidas
				var chr= String.fromCharCode(evt);      // pegando a tecla digitada
				
				if (valid_chars.indexOf(chr)>-1 ) // se a tecla estiver na lista de permissão permite-a
				{
					return true;
				}
				
				return false;   // do contrário nega
			}
		}
		else if (atr == "CPF")
		{
			campos[cont].maxLength = 14;
			
			campos[cont].onblur = function()
			{
				var valorCampo = this.value;
				var valorFiltrado = "";
				var quant = 0;
				
				for (cont = 0; cont <= valorCampo.length - 1; cont = cont + 1)
				{
					if (valorCampo.charAt(cont) >= 0 && valorCampo.charAt(cont) <= 9 && valorCampo.charAt(cont) != " ")
					{
						valorFiltrado = valorFiltrado + valorCampo.charAt(cont);
					}
					
					if (valorCampo.charAt(cont) == '.' && (cont == 3 || cont == 7))
					{
						valorFiltrado = valorFiltrado + valorCampo.charAt(cont);

					}
					
					if (valorCampo.charAt(cont) == '-' && cont == 11)
					{
						valorFiltrado = valorFiltrado + valorCampo.charAt(cont);
					}
				}
				
				this.value = mascaraCPF(valorFiltrado);
				this.value = this.value.trim();
				
				if (this.value != "")
				{
					var cpf = this.value;
					xcpf = cpf.replace("-",".");
					vetorcpf = xcpf.split(".");
					
					// Separando por conjuntos
					var conjunto1 = vetorcpf[0];
					var conjunto2 = vetorcpf[1];
					var conjunto3 = vetorcpf[2];
					var conjunto4 = vetorcpf[3];
					
					if (conjunto1 == undefined || conjunto2 == undefined || conjunto3 == undefined || conjunto4 == undefined)
					{
						this.setAttribute("correto", 0);
					}
					else
					{
						// separando os conjuntos em dígitos separados
						vetor_conjunto1 = conjunto1.split("");
						vetor_conjunto2 = conjunto2.split("");
						vetor_conjunto3 = conjunto3.split("");
						vetor_conjunto4 = conjunto4.split("");
						
						var dig1_conj1 = vetor_conjunto1[0];
						var dig2_conj1 = vetor_conjunto1[1];
						var dig3_conj1 = vetor_conjunto1[2];

						var dig1_conj2 = vetor_conjunto2[0];
						var dig2_conj2 = vetor_conjunto2[1];
						var dig3_conj2 = vetor_conjunto2[2];

						var dig1_conj3 = vetor_conjunto3[0];
						var dig2_conj3 = vetor_conjunto3[1];
						var dig3_conj3 = vetor_conjunto3[2];

						var dig1_conj4 = vetor_conjunto4[0];
						var dig2_conj4 = vetor_conjunto4[1];

						// Obtenção primeiro dígito verificador
						 
						var xdig1_conj1 = dig1_conj1 * 10;
						var xdig2_conj1 = dig2_conj1 * 9;
						var xdig3_conj1 = dig3_conj1 * 8;
						
						var xdig1_conj2 = dig1_conj2 * 7;
						var xdig2_conj2 = dig2_conj2 * 6;
						var xdig3_conj2 = dig3_conj2 * 5;
						
						var xdig1_conj3 = dig1_conj3 * 4;
						var xdig2_conj3 = dig2_conj3 * 3;
						var xdig3_conj3 = dig3_conj3 * 2;
						
						var somatorio = xdig1_conj1+xdig2_conj1+xdig3_conj1+xdig1_conj2+xdig2_conj2+xdig3_conj2+xdig1_conj3+xdig2_conj3+xdig3_conj3;
						var resto = somatorio%11;

						if (resto < 2)
						{
							var digito = "0";
						}
						else
						{
							var digito=11-resto;
						}

						//------ Segundo Digito
						
						var xxdig1_conj1 = dig1_conj1 * 11;
						var xxdig2_conj1 = dig2_conj1 * 10;
						var xxdig3_conj1 = dig3_conj1 * 9;
						
						var xxdig1_conj2 = dig1_conj2 * 8;
						var xxdig2_conj2 = dig2_conj2 * 7;
						var xxdig3_conj2 = dig3_conj2 * 6;
						
						var xxdig1_conj3 = dig1_conj3 * 5;
						var xxdig2_conj3 = dig2_conj3 * 4;
						var xxdig3_conj3 = dig3_conj3 * 3;
						
						var xxdig1_conj4 = dig1_conj4 * 2;
						
						var somatorio =xxdig1_conj1+xxdig2_conj1+xxdig3_conj1+xxdig1_conj2+xxdig2_conj2+xxdig3_conj2+xxdig1_conj3+xxdig2_conj3+xxdig3_conj3+xxdig1_conj4;
						var xresto = somatorio%11;
											
						if (xresto < 2)
						{
							var xdigito = "0"
						}
						else 
						{
							var xdigito = 11-xresto;
						}
						
						var aux = this.value;
						
						aux = aux.replace('.', "");
						aux = aux.replace('.', "");
						aux = aux.replace('.', "");
						aux = aux.replace('-', "");
						
						var somaIgual = 0;
						var somaDiferente = 0;
						
						for (cont = 0; cont <= aux.length - 1; cont = cont + 1)
						{
							if (cont != 0)
							{
								if (aux.charAt(0) == aux.charAt(cont))
								{
									somaIgual = 1;
								}
								else
								{
									somaIgual = 0;
									break;
								}
							}
						}
						
						for (cont = 0; cont <= aux.length - 1; cont = cont + 1)
						{
							somaDiferente = somaDiferente + parseInt(aux.charAt(cont));
						}
						
						if (somaIgual == 1 || somaDiferente == 54)
						{
							this.setAttribute("correto", 0);
						}
						else
						{
							if ((digito != dig1_conj4 || xdigito != dig2_conj4))
							{
								this.setAttribute("correto", 0);
							}
							else
							{
								this.setAttribute("correto", 1);
							}
						}
					}
				}
				else
				{
					this.setAttribute("correto", 0);
				}
			}
			
			campos[cont].onkeypress = function(e,args)
			{
				if (document.all) // caso seja IE
				{
					var evt=event.keyCode;
				}			
				else // do contrário deve ser Mozilla ou Google
				{
					var evt = e.charCode;
				}
				
				var valid_chars = '1234567890.-';      // criando a lista de teclas permitidas
				var chr= String.fromCharCode(evt);      // pegando a tecla digitada
				
				if (valid_chars.indexOf(chr)>-1 || evt < 9)
				{
					var valorCampo = this.value;
					var valorFiltrado = "";
					var quant = 0;
					
					var index = valid_chars.indexOf(chr);
					var tecla = valid_chars.charAt(index);
					
					var tamanho = valorCampo.length;
					
					if (tecla == '.' && (tamanho == 3 || tamanho == 7))
					{
						return true;
					}
					
					if (tecla == '-' && tamanho == 11)
					{
						return true;
					}
					
					if (tecla >= 0)
					{
						if (tamanho == 3 || tamanho == 7 || tamanho == 11)
						{
							valorCampo = mascaraCPF(valorCampo);
							this.value = valorCampo;
							return true;
						}
						else
						{
							return true;
						}
					}
				}
				
				return false;   // do contrário nega
			}
		}
		else if (atr == "RG")
		{
			campos[cont].maxLength = 12;
			
			campos[cont].onblur = function()
			{
				var valorCampo = this.value;
				var valorFiltrado = "";
				var filtro = "12345678909.-";
					
				for (cont = 0; cont <= valorCampo.length - 1; cont = cont + 1)
				{
					if (filtro.indexOf(valorCampo.charAt(cont)) != -1)
					{
						valorFiltrado = valorFiltrado + valorCampo.charAt(cont);
					}
				}
				
				this.value = mascaraRG(valorFiltrado);
				this.value = this.value.trim();
				
				if (this.value != "")
				{
					var rg = this.value;
					
					while(rg.indexOf('.') != -1 || rg.indexOf('-') != -1)
					{
						rg = rg.replace('-', '');
						rg = rg.replace('.', '');
					}
					
					if (rg.length == 9)
					{
						var calculo = "";
							calculo = rg.split("");
						var mult = 2;
						var acu = 0;
						var cont = 0;
						
						for (cont = 0; cont <= 7; cont = cont + 1)
						{
							calculo[cont] = rg[cont];
							calculo[cont] = parseInt(calculo[cont]);
							calculo[cont] = calculo[cont]*mult;
							acu = acu + calculo[cont];
							mult = mult + 1;
						}
						
						calculo[8] = rg[8];
						calculo[8] = parseInt(calculo[8]);
						calculo[8] = calculo[8]*100;
						acu = acu + calculo[8];
						acu = acu%11;
						
						if (acu == 0)
						{
							this.setAttribute("correto", 1);
						}
						else
						{
							this.setAttribute("correto", 0);
						}
					}
					else
					{
						this.setAttribute("correto", 0);
					}	
				}
				else
				{
					this.setAttribute("correto", 0);
				}
			}
			
			campos[cont].onkeypress = function(e,args)
			{
				if (document.all) // caso seja IE
				{
					var evt=event.keyCode;
				}			
				else // do contrário deve ser Mozilla ou Google
				{
					var evt = e.charCode;
				}
				
				var valid_chars = '1234567890-.';      // criando a lista de teclas permitidas
				var chr= String.fromCharCode(evt);      // pegando a tecla digitada
				
				if (valid_chars.indexOf(chr)>-1 ) // se a tecla estiver na lista de permissão permite-a
				{
					var valorCampo = this.value;
					var valorFiltrado = "";
					var quant = 0;
					
					var index = valid_chars.indexOf(chr);
					var tecla = valid_chars.charAt(index);
					
					var tamanho = valorCampo.length;
					
					if (tecla == '.' && (tamanho == 2 || tamanho == 6))
					{
						return true;
					}
					else if (tecla == '-' && tamanho == 10)
					{
						return true;
					}
					else if (tecla >= 0)
					{
						if (tamanho == 2 || tamanho == 6 || tamanho == 10)
						{
							valorCampo = mascaraRG(valorCampo);
							this.value = valorCampo;
							return true;
						}
						else
						{
							return true;
						}
					}
				}
				
				return false;   // do contrário nega
			}
		}
		else if (atr == "TextoNaoObrigatorio")
		{
			campos[cont].onblur = function()
			{
				var valorCampo = this.value;
				var valorFiltrado = "";
				var filtro = "1234567890-&()abcdefghijlmnopqrstuvxzwykçáàâãéèêíìîóòõôúùûQWERTYUIOPASDFGHJKLÇZXCVBNMÁÀÂÃÉÈÊÍÌÎÓÒÕÔÚÙÛ /,.ªº?:*!@#$%_";
					
				for (cont = 0; cont <= valorCampo.length - 1; cont = cont + 1)
				{
					if (filtro.indexOf(valorCampo.charAt(cont)) != -1)
					{
						valorFiltrado = valorFiltrado + valorCampo.charAt(cont);
					}
				}
				
				this.value = valorFiltrado;
				this.value = this.value.trim();
				
				this.setAttribute("correto", 1);
				
			}
			
			campos[cont].onkeypress = function(e,args)
			{
				if (document.all) // caso seja IE
				{
					var evt=event.keyCode;
				}			
				else // do contrário deve ser Mozilla ou Google
				{
					var evt = e.charCode;
				}
				
				var valid_chars = '1234567890-&()abcdefghijlmnopqrstuvxzwykçáàâãéèêíìîóòõôúùûQWERTYUIOPASDFGHJKLÇZXCVBNMÁÀÂÃÉÈÊÍÌÎÓÒÕÔÚÙÛ /,.ªº?:*!@#$%_';      // criando a lista de teclas permitidas
				var chr= String.fromCharCode(evt);      // pegando a tecla digitada
				
				if (valid_chars.indexOf(chr)>-1 ) // se a tecla estiver na lista de permissão permite-a
				{
					return true;
				}
				
				return false;   // do contrário nega
			}
		}
		else if (atr == "TextoObrigatorio" || atr == "Texto")
		{
			campos[cont].onblur = function()
			{
				var valorCampo = this.value;
				var valorFiltrado = "";
				var filtro = "1234567890-&()abcdefghijlmnopqrstuvxzwykçáàâãéèêíìîóòõôúùûQWERTYUIOPASDFGHJKLÇZXCVBNMÁÀÂÃÉÈÊÍÌÎÓÒÕÔÚÙÛ /,.ªº?*:$!@#%_";
					
				for (cont = 0; cont <= valorCampo.length - 1; cont = cont + 1)
				{
					if (filtro.indexOf(valorCampo.charAt(cont)) != -1)
					{
						valorFiltrado = valorFiltrado + valorCampo.charAt(cont);
					}
				}
				
				this.value = valorFiltrado;
				this.value = this.value.trim();
				
				if (this.value != "")
				{
					//validação de RG
					this.setAttribute("correto", 1);
				}
				else
				{
					this.setAttribute("correto", 0);
				}
			}
			
			campos[cont].onkeypress = function(e,args)
			{
				if (document.all) // caso seja IE
				{
					var evt=event.keyCode;
				}			
				else // do contrário deve ser Mozilla ou Google
				{
					var evt = e.charCode;
				}
				
				var valid_chars = '1234567890-&()abcdefghijlmnopqrstuvxzwykçáàâãéèêíìîóòõôúùûQWERTYUIOPASDFGHJKLÇZXCVBNMÁÀÂÃÉÈÊÍÌÎÓÒÕÔÚÙÛ /,.ªº?:*!@#$%_';      // criando a lista de teclas permitidas
				var chr= String.fromCharCode(evt);      // pegando a tecla digitada
				
				if (valid_chars.indexOf(chr)>-1 ) // se a tecla estiver na lista de permissão permite-a
				{
					return true;
				}
				
				return false;   // do contrário nega
			}
		}
		else if (atr == "IMEI")
		{
			campos[cont].maxLength = 19;
			
			campos[cont].onkeypress = function(e,args)
			{
				if (document.all) // caso seja IE
				{
					var evt=event.keyCode;
				}			
				else // do contrário deve ser Mozilla ou Google
				{
					var evt = e.charCode;
				}
				
				var valid_chars = "1234567890-";      // criando a lista de teclas permitidas
				var chr = String.fromCharCode(evt);      // pegando a tecla digitada
				
				if (valid_chars.indexOf(chr)>-1 ) // se a tecla estiver na lista de permissão permite-a
				{
					var valorCampo = this.value;
					var index = valid_chars.indexOf(chr);
					var tecla = valid_chars.charAt(index);
					
					var tamanho = valorCampo.length;
					
					if (tecla == '-' && (tamanho == 2 || tamanho == 7 || tamanho == 10 || tamanho == 17))
					{
						return true;
					}
					else if (tecla >= 0)
					{
						if (tamanho == 2 || tamanho == 7 || tamanho == 10 || tamanho == 17)
						{
							valorCampo = mascaraIMEI(valorCampo);
							this.value = valorCampo;
							return true;
						}
						else
						{
							return true;
						}
					}
				}
				
				return false;   // do contrário nega
			}
			
			campos[cont].onblur = function()
			{
				var valorCampo = this.value;
				var valorFiltrado = "";
				var quant = 0;
				var filtro = "1234567890-";
					
				for (cont = 0; cont <= valorCampo.length - 1; cont = cont + 1)
				{
					if (filtro.indexOf(valorCampo.charAt(cont)) != -1)
					{
						valorFiltrado = valorFiltrado + valorCampo.charAt(cont);
					}
				}
				
				this.value = mascaraIMEI(valorFiltrado);
				this.value = this.value.trim();
				
				if (this.value.length == this.maxLength)
				{
					var IMEI = this.value;
					
					while(IMEI.indexOf('-') != -1 || IMEI.indexOf('.') != -1)
					{
						IMEI = IMEI.replace('-', '');
						IMEI = IMEI.replace('.', '');
					}
					
					var digitoVerificador = IMEI[IMEI.length - 1];
					var calculo = IMEI.split("");
					calculo.splice(calculo.length - 1, 1);
					var soma = 0;
					var cont;
					
					for (cont = calculo.length; cont >= 1; cont = cont - 1)
					{
						if (cont%2 == 0)
						{
							if ((parseInt(calculo[cont - 1])*2) >= 10)
							{
								aux = (parseInt(calculo[cont - 1])*2);
								aux = aux.toString().split("");
								
								aux = parseInt(aux[0]) + parseInt(aux[1]);
								
								soma = soma + aux;
							}
							else
							{
								soma = soma + (parseInt(calculo[cont - 1])*2);
							}
						}
						else
						{
							soma = soma + parseInt(calculo[cont - 1]);
						}
					}
					
					var calculoDigito = soma%10;
					
					if (calculoDigito != 0)
					{
						calculoDigito = 10 - soma%10;
					}
					
					if (digitoVerificador == calculoDigito)
					{
						this.setAttribute("correto", 1);
					}
					else
					{
						this.setAttribute("correto", 0);
					}
				}
				else
				{
					this.setAttribute("correto", 0);
				}
			}
		}
		else if (atr == "Real")
		{
			campos[cont].maxLength = 11;
			
			campos[cont].onkeypress = function(e,args)
			{
				if (document.all) // caso seja IE
				{
					var evt=event.keyCode;
				}			
				else // do contrário deve ser Mozilla ou Google
				{
					var evt = e.charCode;
				}
				
				var valid_chars = "R$1234567890, -";      // criando a lista de teclas permitidas
				var chr = String.fromCharCode(evt).toUpperCase();      // pegando a tecla digitada
				var tecla = chr;
				
				if (valid_chars.indexOf(chr) > -1) // se a tecla estiver na lista de permissão permite-a
				{
					var valorCampo = this.value;
					var index = valid_chars.indexOf(chr);
					
					var tamanho = valorCampo.length;
					
					if (tecla == 'R' && tamanho == 0)
					{
						return true;
					}
					else if (tecla == '$' && tamanho == 1)
					{
						return true;
					}
					else if (tecla == " " && tamanho == 2)
					{
						return true;
					}
					else if (tecla == '-' && tamanho == 3)
					{
						return true;
					}
					else if (tecla == ',' && this.value.indexOf(tecla) == -1)
					{
						return true;
					}
					else if (tecla >= 0)
					{
						if (tamanho == 0)
						{
							valorCampo = mascaraReal(valorCampo);
							this.value = valorCampo;
						}
						
						return true;
					}
				}
				
				return false;   // do contrário nega
			}
			
			campos[cont].onblur = function()
			{
				var valorCampo = this.value;
				var valorFiltrado = "";
				var quant = 0;
				var filtro = "R$1234567890, -";
					
				while(valorCampo.indexOf('.') != -1)
				{
					valorCampo = valorCampo.replace('.', ',');
				}
				
				for (cont = 0; cont <= valorCampo.length - 1; cont = cont + 1)
				{
					if (valorCampo.charAt(cont) == 'R' && cont == 0)
					{
						valorFiltrado = valorFiltrado + valorCampo.charAt(cont);
					}
					else if (valorCampo.charAt(cont) == '$' && cont == 1)
					{
						valorFiltrado = valorFiltrado + valorCampo.charAt(cont);
					}
					else if (valorCampo.charAt(cont) == " " && cont == 2)
					{
						valorFiltrado = valorFiltrado + valorCampo.charAt(cont);
					}
					else if (valorCampo.charAt(cont) == '-' && cont == 3)
					{
						valorFiltrado = valorFiltrado + valorCampo.charAt(cont);
					}
					else if (valorCampo.charAt(cont) == ',' && valorFiltrado.indexOf(valorCampo.charAt(cont)) == -1)
					{
						valorFiltrado = valorFiltrado + valorCampo.charAt(cont);
					}
					else if (filtro.indexOf(valorCampo.charAt(cont)) != -1)
					{
						valorFiltrado = valorFiltrado + valorCampo.charAt(cont);
					}
				}
				
				this.value = mascaraReal(valorFiltrado);
				this.value = this.value.trim();
				
				var real = this.value;
				
				while(real.indexOf('R') != -1 || real.indexOf('$') != -1 || real.indexOf(' ') != -1)
				{
					real = real.replace('R', '');
					real = real.replace('$', '');
					real = real.replace(' ', '');
				}
				
				if (real != '')
				{
					this.setAttribute("correto", 1);
				}
				else
				{
					this.setAttribute("correto", 0);
				}
			}
		}
		else if (atr == "Inteiro" || atr == "NumeroInteiro" || atr == "Int" || atr == "int")
		{
			campos[cont].maxLength = 11;
			
			campos[cont].onkeypress = function(e,args)
			{
				if (document.all) // caso seja IE
				{
					var evt=event.keyCode;
				}			
				else // do contrário deve ser Mozilla ou Google
				{
					var evt = e.charCode;
				}
				
				var valid_chars = "1234567890";      // criando a lista de teclas permitidas
				var chr = String.fromCharCode(evt);      // pegando a tecla digitada
				
				if (valid_chars.indexOf(chr)>-1 ) // se a tecla estiver na lista de permissão permite-a
				{
					return true;
				}
				
				return false;   // do contrário nega
			}
			
			campos[cont].onblur = function()
			{
				var valorCampo = this.value;
				var valorFiltrado = "";
				var quant = 0;
				var filtro = "1234567890";
					
				for (cont = 0; cont <= valorCampo.length - 1; cont = cont + 1)
				{
					if (filtro.indexOf(valorCampo.charAt(cont)) != -1)
					{
						valorFiltrado = valorFiltrado + valorCampo.charAt(cont);
					}
				}
				
				this.value = valorFiltrado;
				
				if (this.value != '')
				{
					this.setAttribute("correto", 1);
				}
				else
				{
					this.setAttribute("correto", 0);
				}
			}
		}
		else if (atr == "numeroSerie")
		{
			campos[cont].maxLength = 20;
			campos[cont].style.textTransform = 'uppercase';
			
			campos[cont].onkeypress = function(e,args)
			{
				if (document.all) // caso seja IE
				{
					var evt=event.keyCode;
				}			
				else // do contrário deve ser Mozilla ou Google
				{
					var evt = e.charCode;
				}
				
				var valid_chars = "1234567890QWERTYUIOPASDFGHJKLÇZXCVBNMqwertyuiopasdfghjklçzxcvbnm";      // criando a lista de teclas permitidas
				var chr = String.fromCharCode(evt);      // pegando a tecla digitada
				
				if (valid_chars.indexOf(chr)>-1 ) // se a tecla estiver na lista de permissão permite-a
				{
					return true;
				}
				
				return false;   // do contrário nega
			}
			
			campos[cont].onblur = function()
			{
				var valorCampo = this.value;
				var valorFiltrado = "";
				var quant = 0;
				var filtro = "1234567890QWERTYUIOPASDFGHJKLZXCVBNMÇqwertyuiopasdfghjklçzxcvbnm";
					
				for (cont = 0; cont <= valorCampo.length - 1; cont = cont + 1)
				{
					if (filtro.indexOf(valorCampo.charAt(cont)) != -1)
					{
						valorFiltrado = valorFiltrado + valorCampo.charAt(cont);
					}
				}
				
				this.value = valorFiltrado;
				
				if (this.value != '')
				{
					this.setAttribute("correto", 1);
				}
				else
				{
					this.setAttribute("correto", 0);
				}
			}
		}
		//
	}
}

function mascaraCelular(valorCampo)
{
	var auxtroca,auxvetor;
	
	while(valorCampo.indexOf('(') != -1 || valorCampo.indexOf(')') != -1 || valorCampo.indexOf(' ') != -1 || valorCampo.indexOf('-') != -1)
	{
		valorCampo = valorCampo.replace('(', '');
		valorCampo = valorCampo.replace(')', '');
		valorCampo = valorCampo.replace(' ', '');
		valorCampo = valorCampo.replace('-', '');
	}
	
	for (posicao = 0; posicao <= valorCampo.length; posicao = posicao + 1)
	{
		if (posicao == 0 && valorCampo.charAt(0) != '(')
		{
			auxvetor = valorCampo.split("");
			auxvetor.splice(posicao, 0, '(');
			
			valorCampo = "";
			for (cont = 0; cont <= auxvetor.length - 1; cont = cont + 1)
			{
				valorCampo = valorCampo + auxvetor[cont];
			}
		}
		
		if (posicao == 3 && valorCampo.charAt(3) != ')')
		{
			auxvetor = valorCampo.split("");
			auxvetor.splice(posicao, 0, ')');
			
			valorCampo = "";
			for (cont = 0; cont <= auxvetor.length - 1; cont = cont + 1)
			{
				valorCampo = valorCampo + auxvetor[cont];
			}
		}
		
		if (posicao == 4 && valorCampo.charAt(4) != " ")
		{
			auxvetor = valorCampo.split("");
			auxvetor.splice(posicao, 0, ' ');
			
			valorCampo = "";
			for (cont = 0; cont <= auxvetor.length - 1; cont = cont + 1)
			{
				valorCampo = valorCampo + auxvetor[cont];
			}
		}
		
		if (posicao == 10 && valorCampo.charAt(10) != '-')
		{
			auxvetor = valorCampo.split("");
			auxvetor.splice(posicao, 0, '-');
			
			valorCampo = "";
			for (cont = 0; cont <= auxvetor.length - 1; cont = cont + 1)
			{
				valorCampo = valorCampo + auxvetor[cont];
			}
		}
	}
	
	return valorCampo;
}

function mascaraReal(valorCampo)
{
	var auxtroca,auxvetor;
	
	valorCampo = valorCampo.toString();
	
	while(valorCampo.indexOf('R') != -1 || valorCampo.indexOf('$') != -1 || valorCampo.indexOf(' ') != -1)
	{
		valorCampo = valorCampo.replace('R', '');
		valorCampo = valorCampo.replace('$', '');
		valorCampo = valorCampo.replace(' ', '');
	}
	
	for (posicao = 0; posicao <= valorCampo.length; posicao = posicao + 1)
	{
		if (posicao == 0 && valorCampo.charAt(0) != 'R')
		{
			auxvetor = valorCampo.split("");
			auxvetor.splice(posicao, 0, 'R');
			
			valorCampo = "";
			for (cont = 0; cont <= auxvetor.length - 1; cont = cont + 1)
			{
				valorCampo = valorCampo + auxvetor[cont];
			}
		}
		
		if (posicao == 1 && valorCampo.charAt(1) != '$')
		{
			auxvetor = valorCampo.split("");
			auxvetor.splice(posicao, 0, '$');
			
			valorCampo = "";
			for (cont = 0; cont <= auxvetor.length - 1; cont = cont + 1)
			{
				valorCampo = valorCampo + auxvetor[cont];
			}
		}
		
		if (posicao == 2 && valorCampo.charAt(2) != " ")
		{
			auxvetor = valorCampo.split("");
			auxvetor.splice(posicao, 0, ' ');
			
			valorCampo = "";
			for (cont = 0; cont <= auxvetor.length - 1; cont = cont + 1)
			{
				valorCampo = valorCampo + auxvetor[cont];
			}
		}
	}
	
	while(valorCampo.indexOf('.') != -1)
	{
		valorCampo = valorCampo.replace('.', ',');
	}
	
	auxvetor = valorCampo.split(',');
	
	while (auxvetor.length > 2)
	{
		auxvetor.splice(auxvetor.length - 1, 1);
	}
	
	if (auxvetor.length == 2)
	{
		var auxdecimal = '';
		
		if (auxvetor[1].toString().length < 2)
		{
			//auxdecimal = auxvetor[1][0] + '0';
		}
		else
		{
			auxdecimal = auxvetor[1][0] + auxvetor[1][1];
			auxvetor.splice(1, 1);
			auxvetor.push(auxdecimal);
		}
		
	}
	
	valorCampo = "";
	for (cont = 0; cont <= auxvetor.length - 1; cont = cont + 1)
	{
		if (cont == 0)
		{
			valorCampo = valorCampo + auxvetor[cont];
		}
		else
		{
			valorCampo = valorCampo + ',' + auxvetor[cont];
		}
	}
	
	return valorCampo;
}

function mascaraFixo(valorCampo)
{
	var auxtroca,auxvetor;
	
	while(valorCampo.indexOf('(') != -1 || valorCampo.indexOf(')') != -1 || valorCampo.indexOf(' ') != -1 || valorCampo.indexOf('-') != -1)
	{
		valorCampo = valorCampo.replace('(', '');
		valorCampo = valorCampo.replace(')', '');
		valorCampo = valorCampo.replace(' ', '');
		valorCampo = valorCampo.replace('-', '');
	}
	
	for (posicao = 0; posicao <= valorCampo.length; posicao = posicao + 1)
	{
		if (posicao == 0 && valorCampo.charAt(0) != '(')
		{
			auxvetor = valorCampo.split("");
			auxvetor.splice(posicao, 0, '(');
			
			valorCampo = "";
			for (cont = 0; cont <= auxvetor.length - 1; cont = cont + 1)
			{
				valorCampo = valorCampo + auxvetor[cont];
			}
		}
		
		if (posicao == 3 && valorCampo.charAt(3) != ')')
		{
			auxvetor = valorCampo.split("");
			auxvetor.splice(posicao, 0, ')');
			
			valorCampo = "";
			for (cont = 0; cont <= auxvetor.length - 1; cont = cont + 1)
			{
				valorCampo = valorCampo + auxvetor[cont];
			}
		}
		
		if (posicao == 4 && valorCampo.charAt(4) != " ")
		{
			auxvetor = valorCampo.split("");
			auxvetor.splice(posicao, 0, ' ');
			
			valorCampo = "";
			for (cont = 0; cont <= auxvetor.length - 1; cont = cont + 1)
			{
				valorCampo = valorCampo + auxvetor[cont];
			}
		}
		
		if (posicao == 9 && valorCampo.charAt(9) != '-')
		{
			auxvetor = valorCampo.split("");
			auxvetor.splice(posicao, 0, '-');
			
			valorCampo = "";
			for (cont = 0; cont <= auxvetor.length - 1; cont = cont + 1)
			{
				valorCampo = valorCampo + auxvetor[cont];
			}
		}
	}
	
	return valorCampo;
}

function mascaraData(valorCampo)
{
	var auxtroca,auxvetor;
	
	while(valorCampo.indexOf('/') != -1)
	{
		valorCampo = valorCampo.replace('/', '');
	}
	
	for (posicao = 0; posicao <= valorCampo.length; posicao = posicao + 1)
	{
		if (posicao == 2 && valorCampo.charAt(2) != '/')
		{
			auxvetor = valorCampo.split("");
			auxvetor.splice(posicao, 0, '/');
			
			valorCampo = "";	
			for (cont = 0; cont <= auxvetor.length - 1; cont = cont + 1)
			{
				valorCampo = valorCampo + auxvetor[cont];
			}
		}
		
		if (posicao == 5 && valorCampo.charAt(5) != '/')
		{
			auxvetor = valorCampo.split("");
			auxvetor.splice(posicao, 0, '/');
			
			valorCampo = "";	
			for (cont = 0; cont <= auxvetor.length - 1; cont = cont + 1)
			{
				valorCampo = valorCampo + auxvetor[cont];
			}
		}
	}
	
	return valorCampo;
}



function mascaraRG(valorCampo)
{
	var auxtroca,auxvetor;
	
	while(valorCampo.indexOf('.') != -1 || valorCampo.indexOf('-') != -1)
	{
		valorCampo = valorCampo.replace('.', '');
		valorCampo = valorCampo.replace('-', '');
	}
	
	for (posicao = 0; posicao <= valorCampo.length; posicao = posicao + 1)
	{
		if (posicao == 2 && valorCampo.charAt(2) != '.')
		{
			auxvetor = valorCampo.split("");
			auxvetor.splice(posicao, 0, '.');
			
			valorCampo = "";
			for (cont = 0; cont <= auxvetor.length - 1; cont = cont + 1)
			{
				valorCampo = valorCampo + auxvetor[cont];
			}
		}
		
		if (posicao == 6 && valorCampo.charAt(6) != '.')
		{
			auxvetor = valorCampo.split("");
			auxvetor.splice(posicao, 0, '.');
			
			valorCampo = "";
			for (cont = 0; cont <= auxvetor.length - 1; cont = cont + 1)
			{
				valorCampo = valorCampo + auxvetor[cont];
			}
		}
		
		if (posicao == 10 && valorCampo.charAt(10) != '-')
		{
			auxvetor = valorCampo.split("");
			auxvetor.splice(posicao, 0, '-');
			
			valorCampo = "";
			for (cont = 0; cont <= auxvetor.length - 1; cont = cont + 1)
			{
				valorCampo = valorCampo + auxvetor[cont];
			}
		}
	}
		
	return valorCampo;
}

function mascaraCPF(valorCampo)
{
	var auxtroca,auxvetor;
	
	while(valorCampo.indexOf('.') != -1 || valorCampo.indexOf('-') != -1)
	{
		valorCampo = valorCampo.replace('.', '');
		valorCampo = valorCampo.replace('-', '');
	}
	
	for (posicao = 0; posicao <= valorCampo.length; posicao = posicao + 1)
	{
		if (posicao == 3 && valorCampo.charAt(3) != '.')
		{
			auxvetor = valorCampo.split("");
			auxvetor.splice(posicao, 0, '.');
			
			valorCampo = "";
			for (cont = 0; cont <= auxvetor.length - 1; cont = cont + 1)
			{
				valorCampo = valorCampo + auxvetor[cont];
			}
		}
		
		if (posicao == 7 && valorCampo.charAt(7) != '.')
		{
			auxvetor = valorCampo.split("");
			auxvetor.splice(posicao, 0, '.');
			
			valorCampo = "";
			for (cont = 0; cont <= auxvetor.length - 1; cont = cont + 1)
			{
				valorCampo = valorCampo + auxvetor[cont];
			}
		}
		
		if (posicao == 11 && valorCampo.charAt(11) != '-')
		{
			auxvetor = valorCampo.split("");
			auxvetor.splice(posicao, 0, '-');
			
			valorCampo = "";
			for (cont = 0; cont <= auxvetor.length - 1; cont = cont + 1)
			{
				valorCampo = valorCampo + auxvetor[cont];
			}
		}
	}
		
	return valorCampo;
}

function mascaraIMEI(valorCampo)
{
	var auxtroca,auxvetor;
	
	while(valorCampo.indexOf('.') != -1 || valorCampo.indexOf('-') != -1)
	{
		valorCampo = valorCampo.replace('.', '');
		valorCampo = valorCampo.replace('-', '');
	}
	
	for (posicao = 0; posicao <= valorCampo.length; posicao = posicao + 1)
	{
		if (posicao == 2 && valorCampo.charAt(2) != '-')
		{
			auxvetor = valorCampo.split("");
			auxvetor.splice(posicao, 0, '-');
			
			valorCampo = "";
			for (cont = 0; cont <= auxvetor.length - 1; cont = cont + 1)
			{
				valorCampo = valorCampo + auxvetor[cont];
			}
		}
		
		if (posicao == 7 && valorCampo.charAt(7) != '-')
		{
			auxvetor = valorCampo.split("");
			auxvetor.splice(posicao, 0, '-');
			
			valorCampo = "";
			for (cont = 0; cont <= auxvetor.length - 1; cont = cont + 1)
			{
				valorCampo = valorCampo + auxvetor[cont];
			}
		}
		
		if (posicao == 10 && valorCampo.charAt(10) != '-')
		{
			auxvetor = valorCampo.split("");
			auxvetor.splice(posicao, 0, '-');
			
			valorCampo = "";
			for (cont = 0; cont <= auxvetor.length - 1; cont = cont + 1)
			{
				valorCampo = valorCampo + auxvetor[cont];
			}
		}
		
		if (posicao == 17 && valorCampo.charAt(17) != '-')
		{
			auxvetor = valorCampo.split("");
			auxvetor.splice(posicao, 0, '-');
			
			valorCampo = "";
			for (cont = 0; cont <= auxvetor.length - 1; cont = cont + 1)
			{
				valorCampo = valorCampo + auxvetor[cont];
			}
		}
	}
		
	return valorCampo;
}