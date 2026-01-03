(()=>{var r=class extends HTMLElement{static get observedAttributes(){return["layout","show-answer"]}attributeChangedCallback(i,a,o){a!==o&&this.connectedCallback()}connectedCallback(){let i=parseInt(this.getAttribute("a"))||0,a=parseInt(this.getAttribute("b"))||1,o=this.getAttribute("operation")||"addition",c=this.getAttribute("layout")==="horizontal"?"horizontal":"vertical",l=this.hasAttribute("show-answer"),e=i,s=a,t="+";switch(o){case"-":case"subtraction":t="-",a>i&&([e,s]=[a,i]);break;case"*":case"multiplication":t="\xD7";break;case"/":case"division":t="\xF7",e=i*a,s=a;break;default:t="+"}let n=0;t==="-"?n=e-s:t==="\xD7"?n=e*s:t==="\xF7"?n=e/s:n=e+s,this.setAttribute("data-layout",c),this.innerHTML=`
            <div class="mathabunga-problem-inner ${l?"has-answer":""}">
                <div class="mathabunga-top">${e}</div>
                <div class="mathabunga-bottom">
                    <span class="mathabunga-operator">${t}</span>
                    ${s}
                </div>
                <div class="mathabunga-answer-space">
                    ${l?`<span class="mathabunga-answer">${n}</span>`:""}
                </div>
            </div>
        `}};customElements.get("mathabunga-problem")||customElements.define("mathabunga-problem",r);})();
//# sourceMappingURL=mathabunga-problem.src.js.map
