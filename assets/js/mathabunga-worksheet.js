(()=>{var o=class extends HTMLElement{static get observedAttributes(){return["count","operations","max","show-answers","layout"]}attributeChangedCallback(){this.generate()}connectedCallback(){this.innerHTML.trim()||this.generate()}generate(){let b=parseInt(this.getAttribute("count"))||12,s=parseInt(this.getAttribute("max"))||10,e=this.getAttribute("layout")||"vertical",u=this.hasAttribute("show-answers"),n=(this.getAttribute("operations")||"+").split(","),t=`<div class="worksheet-grid ${e==="horizontal"?"is-horizontal":""}">`,a=t;for(let r=0;r<b;r++){let i=n[Math.floor(Math.random()*n.length)],l=Math.floor(Math.random()*(s+1)),h=Math.floor(Math.random()*(s+1));t+=`
                <mathabunga-problem
                    a="${l}"
                    b="${h}"
                    operation="${i}"
                    layout="${e}"
				>
                </mathabunga-problem>`,a+=`
                <mathabunga-problem
                    a="${l}"
                    b="${h}"
                    operation="${i}"
                    layout="${e}"
                    show-answer
				>
                </mathabunga-problem>`}t+="</div>",a+="</div>",this.innerHTML=t+a}};customElements.define("mathabunga-worksheet",o);})();
//# sourceMappingURL=mathabunga-worksheet.src.js.map
