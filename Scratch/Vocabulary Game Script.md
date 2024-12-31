Here's a detailed script for each level of your vocabulary game in Scratch. This script assumes you have a basic understanding of creating sprites, backdrops, and scripts in Scratch.

---

### **Level 1: Multiple Choice Quiz**

#### **Sprites Needed**
1. `Text Box` for displaying questions and words.
2. `Start Button`.
3. `Options` (3 buttons for multiple-choice).
4. `Next Button` (to proceed to the next word).
5. `Audio sprite` for pronunciation sounds.

#### **Scripts**

##### **Backdrop:**
1. Set the initial backdrop (e.g., "Welcome Screen").
2. On Green Flag clicked:
   - `switch backdrop to Welcome Screen`
   - `hide Start Button`
   - `hide Options`
   - `hide Next Button`

##### **Start Button:**
1. When the Green Flag is clicked:
   - `show`
2. When this sprite clicked:
   - `hide`
   - `broadcast "Level 1"`

##### **Words and Pronunciation Logic:**
1. Create a list called `Words` with 10 vocabulary words.
2. Create another list called `Meanings` with their correct meanings.
3. Add the pronunciation audio files to the Audio sprite.

##### **Text Box Sprite:**
1. When I receive "Level 1":
   - `switch backdrop to "Level 1"`
   - `set [Score] to (0)`
   - `show`

2. Loop for each word:
   - Display the word and play the pronunciation:
     ```scratch
     say (item (wordIndex) of [Words]) for (2) seconds
     broadcast "Play Audio"
     ```
   - Show 3 options (1 correct, 2 random wrong answers).

##### **Option Buttons:**
1. When this sprite clicked:
   - If correct:
     ```scratch
     change [Score] by (1)
     say "Correct!" for (2) seconds
     ```
   - Else:
     ```scratch
     say "Wrong! The correct answer is (item (wordIndex) of [Meanings])." for (3) seconds
     ```

##### **Next Button:**
1. When this sprite clicked:
   - Broadcast "Next Word".

Repeat for all 10 words. After 10 words, broadcast "Level 2".

---

### **Level 2: Drag and Drop Match**

#### **Sprites Needed**
1. `Word Boxes` (10 sprites for words).
2. `Meaning Boxes` (10 sprites for meanings).

#### **Scripts for Word Boxes:**
1. When Green Flag clicked:
   - Position them randomly or in a grid.
   - Make draggable.

2. When this sprite is dropped:
   - If touching correct Meaning Box:
     ```scratch
     change [Score] by (1)
     say "Correct!" for (2) seconds
     ```
   - Else:
     ```scratch
     say "Wrong! The correct meaning is (item (wordIndex) of [Meanings])." for (3) seconds
     ```

#### **Scripts for Meaning Boxes:**
1. Position them statically on the screen.

After all words are matched, broadcast "Level 3".

---

### **Level 3: Search and Find a Word**

#### **Sprites Needed**
1. `Sentence Box` (static sprite for displaying sentences).
2. `Highlight Sprite` (to animate the highlight).
3. `Answer Box` for user input.

#### **Scripts for Sentence Box:**
1. When I receive "Level 3":
   - Display the first sentence.
   - Animate the highlight:
     ```scratch
     glide (1) secs to [position of word in the sentence]
     ```
   - Ask the user:
     ```scratch
     ask "What is the highlighted word?" and wait
     ```
   - If answer is correct:
     ```scratch
     change [Score] by (1)
     play sound [Correct Answer]
     ```
   - Else:
     ```scratch
     play sound [Wrong Answer]
     ```

Repeat for 10 sentences.

---

### **Final Score Display**
1. After Level 3, switch to a final backdrop:
   - Display the score using:
     ```scratch
     say "Your total score is [Score]!" for (5) seconds
     ```

---

Let me know if you need further clarification or help with Scratch blocks!