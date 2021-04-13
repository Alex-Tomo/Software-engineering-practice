<?php
    include('../pageTemplate.php');
    $page = new pageTemplate('Logged In Home');
    $page->addCSS("<link rel=\"stylesheet\" href=\"./css/styling.css\">");
    $page->addCSS("<link rel=\"stylesheet\" href=\"./css/footerStyling.css\">");
    $page->addCSS("<link rel=\"stylesheet\" href=\"./css/headerStyling.css\">");
    $page->addJavaScript("<script src=\"./js/navBar.js\"></script>");
    $page->addJavaScript("<script src=\"./js/popupForm.js\"></script>");

    $page->addPageBodyItem("
<div id='pageContainer'>
    <div class='popup' id='popup-1'>
        <div class='overlay'></div>
            <form id='regForm' action='/action_page.php'>
                <div id='tab1'>
                    <p>Tell us about yourself</p>
                    <h1>General information</h1>
                    <label for='fname'>First name</label><br>
                    <input type='text' id='fname' name='fname' placeholder='Your name'><br>
                    <label for='lname'>Last name</label><br>
                    <input type='text'  id='lname' name='lname' placeholder='Last name'><br>
                    <label for='gender'>Gender</label>
                    <select id='gender' name='gender'>
                        <option value='0'> Select </option>
                        <option value='male'>Male</option>
                        <option value='female'>Female</option>
                        <option value='pnts'>Prefer not to say</option>
                    </select>
                    <label for='lang'>Preferred language</label><br>
                    <select id='lang' name='lang'>
                        <option value='0'>Choose your language</option>
                        <option value='en'>English</option>
                        <option value='fr'>French</option>
                        <option value='de'>German</option>
                        <option value='da'>Danish</option>
                        <option value='es'>Spanish</option>
                        <option value='it'>Italian</option>
                        <option value='fi'>Finnish</option>
                        <option value='no'>Norwegian</option>
                        <option value='sv'>Swedish</option>
                        <option value='zh'>Chinese</option>
                        <option value='ja'>Japanese</option>
                        <option value='ar'>Arabic</option>
                        <option value='el'>Greek</option>
                        <option value='lt'>Lithuanian</option>
                        <option value='pl'>Polish</option>
                        <option value='ru'>Russian</option>
                        
                    </select>
                    <label for='reg'>Region</label><br>
                    <select id='reg' name='reg'>
                        <option value='0'>Choose your region</option>
                        <option value='uk'>United Kingdom</option>
                        <option value='france'>France</option>
                        <option value='germany'>Germany</option>
                        <option value='denmark'>Denmark</option>
                        <option value='spain'>Spain</option>
                        <option value='italy'>Italy</option>
                        <option value='finland'>Finland</option>
                        <option value='norway'>Norway</option>
                        <option value='sweden'>Sweden</option>
                        <option value='china'>China</option>
                        <option value='japan'>Japan</option>
                        <option value='uae'>United Arab Emirates</option>
                        <option value='usa'>United States</option>
                        <option value='greece'>Greece</option>
                        <option value='lithuania'>Lithuania</option>
                        <option value='poland'>Poland</option>
                        <option value='russia'>Russia</option>
                    </select>
                    <button type='button' id='nextBtn1'>Next</button>               
                </div>
  
                <div id='tab2'>
                    <p>Tell us about yourself</p>
                    <h1>Which one are you?</h1>
                    <div class='box' id='topBox'>
                        <img src='assets/people_searching.svg'>  
                        <h2>Offering a job</h2> 
                    </div>
                    <div class='box' id='btmBox'>
                        <img src='assets/file_searching.svg'> 
                        <h2>Searching for a job</h2> 
                    </div>                   
                    <button type='button' id='nextBtn2'>Next</button>
                    <button type='button' id='prevBtn2'>Back</button>
                </div>
                
                <div id='tab3'>
                    <p>Tell us about yourself</p>
                    <h1>What job are you searching for?</h1>
                    <label>Category (you may choose a few)</label>
                    <input type='search' placeholder='Search for category'>                 
                    <button type='button' id='nextBtn3'>Next</button>
                    <button type='button' id='prevBtn3'>Back</button>
                </div>
                
                <div id='tab4'>
                    <p>Tell us about yourself</p>
                    <h1>How much do you charge?</h1>
                    <label>Your hourly rate</label>
                    <input type='text' placeholder='e.g. £12.00/h'>                 
                    <button type='button' id='nextBtn4'>Submit</button>
                    <button type='button' id='prevBtn4'>Back</button>
                </div>
            </form>
        </div>
    </div>       

    <div id='refineContainer'>
        <div class='refineChild'>
            <label>Keyword</label><br>
            <input type='text' name='keyword' value='Search keyword here'>
        </div>
        
        <div class='refineChild'>
            <label>Categories</label><br>
            <select name='categories'>
                <option value='javaprogrammer'>Java Programmer</option>
                <option value='developer'>Developer</option>
                <option value='programmer'>Programmer</option>
            </select>
        </div>
        
        <div class='refineChild'>
            <label>Junior</label><br>
            <select name='skillLevel'>
                <option value='junior'>Junior</option>
                <option value='beginner'>Beginner</option>
                <option value='intermediate'>Intermediate</option>
                <option value='advanced'>Advanced</option>
                <option value='expert'>Expert</option>
            </select>
        </div>
            <button type='submit'><i id='magGlass' class='fa fa-search'></i>Search</button>
    </div>
    
    <div id='resultContainer'>
        <h1>Recommended for you</h1>
        
        <div class='resultChild'>
            <img src=''>
            <div class='resultText'>
                <img src='assets/photo.svg'>
                <h2>Potential employer</h2>
                <h3>A Python developer is needed</h3>
                <p>Text to write in a job description and Text to write in a job description and Text to write in a job description and</p>
                <span class='fa fa-star checked'></span>
                <span class='fa fa-star checked'></span>
                <span class='fa fa-star checked'></span>
                <span class='fa fa-star checked'></span>
                <span class='fa fa-star'></span>
                <p class='price'>£12.00/h</p>
            </div>
        </div>
        
        <div class='resultChild'>
            <img src='assets/photo.svg'>
            <div class='resultText'>
                <img src>
                <h2>Potential employer</h2>
                <h3>A Python developer is needed</h3>
                <p>Text to write in a job description and Text to write in a job description and Text to write in a job description and</p>
                <span class='fa fa-star checked'></span>
                <span class='fa fa-star checked'></span>
                <span class='fa fa-star checked'></span>
                <span class='fa fa-star checked'></span>
                <span class='fa fa-star'></span>
                <p class='price'>£12.00/h</p>
            </div>
        </div>
        
        <div class='resultChild'>
            <img src='assets/photo.svg'>
            <div class='resultText'>
                <img src>
                <h2>Potential employer</h2>
                <h3>A Python developer is needed</h3>
                <p>Text to write in a job description and Text to write in a job description and Text to write in a job description and</p>
                <span class='fa fa-star checked'></span>
                <span class='fa fa-star checked'></span>
                <span class='fa fa-star checked'></span>
                <span class='fa fa-star checked'></span>
                <span class='fa fa-star'></span>
                <p class='price'>£12.00/h</p>
            </div>
        </div>
    </div>   
    
    <div id='categories'>
        <h1>Popular categories</h1>
        <button type='submit'>Developer</button>
        <button type='submit'>Bank Accountant</button>
        <button type='submit'>Bank Accountant</button>        
        <button type='submit'>Developer</button>
        <button type='submit'>Developer</button>
        <button type='submit'>Bank Accountant</button>
        <button type='submit'>Bank Accountant</button>        
        <button type='submit'>Developer</button>
        <button type='submit'>Developer</button>
        <button type='submit'>Bank Accountant</button>
    </div>  
    
    <div id='recViewedParent'>
        <h1>Recently viewed</h1>
        <div id='recViewedChild'>
            <div id='replaceWithImg'></div>
            <h4>A Python developer is needed for a project</h4>
            <p>£12.00/h</p>  
        </div>
        
        <div id='recViewedChild'>
            <div id='replaceWithImg'></div>
            <h4>A Python developer is needed for a project</h4>
            <p>£12.00/h</p>  
        </div>
        
        <div id='recViewedChild'>
            <div id='replaceWithImg'></div>
            <h4>A Python developer is needed for a project</h4>
            <p>£12.00/h</p>  
        </div>
        
        <div id='recViewedChild'>
            <div id='replaceWithImg'></div>
            <h4>A Python developer is needed for a project</h4>
            <p>£12.00/h</p>  
        </div>
    </div>

</div>");

    $page->displayPage();
?>

<!--    <img src='assets/appStore.svg' alt='App Store'>-->
<!--    <img src='assets/googlePlay.svg' alt='App Store'>-->

