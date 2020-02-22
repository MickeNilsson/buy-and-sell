<?php

function sendMessage($ad_aa, $message_s) {

    // To send HTML mail, the Content-type header must be set
    //$headers[] = 'MIME-Version: 1.0';
    $htmlMail_s = '<!DOCTYPE html>'
                . '<html lang="sv">'
                . '<head>'
                . '<meta charset="UTF-8">'
                . '<title>Document</title>'
                . '</head>'
                . '<body>'
                . '<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAIcAAAAYCAIAAABftXnLAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAACxIAAAsSAdLdfvwAAAcfSURBVGhD7Zl5iM1tFMfPDK99G8sM2Zns+05E1kTJPmQLiRDZikSWbFlGRBkZuyyRRFmLaIgh/jBETJYhWV/7kvt+e853fu/jd3+X+5trbnrf+fwxc57vc567PMs55/ndmEAgIHn8YcTyfx5/Ejwra9asWb9+/V9BqFOlSpUSExPHjBkDQ5Vogvc9ffq02rGxsffu3VM7TBo3bvzmzRu1mzZteujQIbVDcf369dTUVLxjVlbWq1evSpQoUbFixcqVK+NjDBw4kE4+SU9P79+/PxsiKSkp3bp1g1GtWjVVQLt27Xbv3s0GVgWsXr2a7dDExMRs27ZN/aMJpoOfQKRGjRpUw6ZBgwYcLDJ27FiqIUhOTqarF127dqWfT65cucKXMJw4cUL1fPnyURIZMmSIisBHBIP3tGnTXr58yXa0iIuLoyVSqlQpWmFjD3dOvydnz56dOnUqG4aSJUvSMpw6dcreIrmH96rUq1dv+vTpCxcunDFjRpEiRaiKvHjxwgkmUcPe7FWqVKEVNg0bNqQlgkBEy4tly5bREilWrBhC2evXr9PS0uw4s2XLlu/fv7ORa+Tn/x9p2bLlypUr1a5evfrEiRPVBk+fPlVj3bp1b9++VRvMmTNHjSVLlqgBatWqNWDAgPnz5xctWlRPK2J0UlKS9oIVK1Z8+PDh69evsLENEaBUt2nTpg0tkfr166uxdu1aNcCUKVPwd8eOHRcvXvz06VOjRo0mTJhQoEAB7W3btu2GDRvUbtasmRqe3L9/n5ZI7dq18Tow8O7YoJMnT0YAR44pXrz43r17EW3UTTl+/HiGAQtWoUIFbKNevXrhK7M7B2ggc+WVkSNHqg769etH1XDmzBnVe/bsSUkkISFBRUDJMGnSJCiFCxdmW6Ru3brqBo4dO0bVgOVhRxD0EDl69Ciart2KMI1txIYBu/vWrVs69u7du1RFkPZV9GTQoEH0M6C0GTFixObNm2/fvk2PIM6dO9e+fXsO+BFsazr5zyveq1K1alUkRuy4wYMHUzJgO6s/sFcFgYWq16osXbqUbUNmZqZ6jh8/npJIx44dVfTk72y0GU4MGTp0qDoDDs4eHort27dzcBA4xLNnz37y5AldDRcuXGB3Nvnz/xB7Vq1apZ6/Z1U8QY5RZ6V3797sMJGKaiBgfzJdFcC2Ye7cuSqizKUkgnOjYjgEr0p8fLydfgAUevth5syZHO9FuXLl9uzZQ9dAALGRHSIofJGEIGrJ64DaGmIurgpANPv8+bMOsSMbMqqKwI6nzqogu1AyiQrKnTt32DaoW5i4VqVDhw6qjxs3jpIB5aLqvjhy5Ejnzp35EkEgu2CK4Xbp0iVKBnwdHQ4oGTZu3Ajl91TGTZo0QQGGA7ho0SIU6VRFDh48iMimtl1lFixYkJZIoUKFaFkg4dMySRXTum/fPrZFfr5Df0mfPn3U6NSpkxrK+/fvafkBMQB1Ju6qW7duHTVqlOvijClDmQPjxo0bqih9+/ZFJYLaFYnTKTQA0hstX+ji/CTbAywSOwyalocNG8a2qdnUE6CmpGqdFdC8eXOqIsuXL0c9w4bIzZs36RQerrMyfPhw1Q8fPkzJ8PDhQ9XDAZsd0Qlbe/HixbiWoZxjRyBgX8sBZgMi0gzbP0U/m9+z4l0Zu3DNwuPHjxMTE+0dgUSqBrbYu3fv1HaB4hJbT23Utc6QFi1aYH+pnTNatWpFKwKuXbuGeWFDBIndef5x4MABZJTnz59rU7edfYkBzkXCAbFOYdsP3hEMteAuA04rdorrtOqNt2zZstoE8Med4OrVq9hllILA+StTpozaWVlZagBXMsgBSHW0IqB79+60DOfPnz958qTaKHOcJQF6f7JTPWjdujWuNQpmBjVbly5dEO1dDwvCRY9MmNke9OjRQ4fY97hQ2BEMzJs3jx0WuIqyO2xcZxfxUPVIIhjA7HNkNrgSli9fng0D9v6DBw/U345scXFxycnJmzZtcvljp8Izd2swFCcIUDoEhaCz9x2Q6lFisRG0KvaOU5yU4ItQq4JihJLB76oA+zmCJ7jY0zUQQI1Xs2ZNdniBG6h6/p4azAb5AzEUZfiCBQvwck7hizi2f/9++xkGfC5fvowbKNtBYBWxDGwY7FAeOZGHsrS0NGT74MdlOAqobjIyMuz7P8T09HRE5oSEBEoG3NiSkpIwFTl+lPnvb5FfvnxRw4Wd1T3BicauxGJoEfnx40fVAT6f6zHt6NGjU1NT1cap8vtjSdRA5nv06BG+C4r+0qVL45rMjhBkZmaiCPr27RuCHhbVfsik2NPrTKlrzh09Gr8QI+OhbsF5RwrZuXMnVRN5Zs2axUYeNiaO5S6epX18fHwO8vz/hF/nlcipU6cOrWyQk1BJ2/fNPGyiEcGePXuGOzMKMJRtKBxxA0XmjOjnh/860ViVPPwh8g9sgXkfPT8OmQAAAABJRU5ErkJggg==" alt="logo" />'
                . '<div>'
                . '<p>Hej, du har fått ett svar på din annons som har rubriken "' . $ad_aa['header'] . '".</p>'
                . '<p>Svar: ' . $message_s . '</p>'
                . '<p>Du hittar din annons <a target="_blank" href="http://digizone.se/buy-and-sell/?id=' . $ad_aa['id'] . '">här</a>.</p>'
                . '<p>För att ta bort din annons, klicka <a target="_blank" href="http://www.digizone.se/buy-and-sell/?delete=' . $ad_aa['uuid'] . '">här</a>.</p>'
                . '</div>'
                . '</body>'
                . '</html>';
    $headers_s = 'Content-Type: text/html; charset=utf-8' . "\r\n"
               . 'From: Buy and Sell <info@digizone.se>';
    $mailWasSent_b = mail($ad_aa['email'], 'Ett svar på din annons på buyandsell.se!', $htmlMail_s, $headers_s);
    return $mailWasSent_b;
}


?>
